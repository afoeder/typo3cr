<?php
declare(ENCODING = 'utf-8');
namespace F3\TYPO3CR\FLOW3\Persistence;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3CR".                    *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * @package TYPO3CR
 * @subpackage FLOW3
 * @version $Id$
 */

/**
 * A data mapper to map nodes to objects
 *
 * @package TYPO3CR
 * @subpackage FLOW3
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class DataMapper {

	/**
	 * @var \F3\FLOW3\Object\ManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var \F3\FLOW3\Object\Builder
	 */
	protected $objectBuilder;

	/**
	 * @var \F3\TYPO3CR\FLOW3\Persistence\IdentityMap
	 */
	protected $identityMap;

	/**
	 * @var \F3\FLOW3\Persistence\Manager
	 */
	protected $persistenceManager;

	/**
	 * Injects a Object Manager
	 *
	 * @param \F3\FLOW3\Object\ManagerInterface $objectManager
	 * @return void
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function injectObjectManager(\F3\FLOW3\Object\ManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * Injects a Object Object Builder
	 *
	 * @param \F3\FLOW3\Object\Builder $objectBuilder
	 * @return void
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function injectObjectBuilder(\F3\FLOW3\Object\Builder $objectBuilder) {
		$this->objectBuilder = $objectBuilder;
	}

	/**
	 * Injects the identity map
	 *
	 * @param \F3\TYPO3CR\FLOW3\Persistence\IdentityMap $identityMap
	 * @return void
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function injectIdentityMap(\F3\TYPO3CR\FLOW3\Persistence\IdentityMap $identityMap) {
		$this->identityMap = $identityMap;
	}

	/**
	 * Injects the persistence manager
	 *
	 * @param \F3\FLOW3\Persistence\Manager $persistenceManager
	 * @return void
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function injectPersistenceManager(\F3\FLOW3\Persistence\Manager $persistenceManager) {
		$this->persistenceManager = $persistenceManager;
	}

	/**
	 * Maps the (aggregate root) nodes and registers them as reconstituted
	 * with the session.
	 *
	 * @param \F3\PHPCR\NodeIteratorInterface $nodes
	 * @return array
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function map(\F3\PHPCR\NodeIteratorInterface $nodes) {
		$objects = array();
		foreach ($nodes as $node) {
			$object = $this->mapSingleNode($node);
			$this->persistenceManager->getSession()->registerReconstitutedObject($object);
			$objects[] = $object;
		}

		return $objects;
	}

	/**
	 * Maps a single node into the object it represents
	 *
	 * @param \F3\PHPCR\NodeInterface $node
	 * @return object
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	protected function mapSingleNode(\F3\PHPCR\NodeInterface $node) {
		if ($this->identityMap->hasUUID($node->getIdentifier())) {
			$object = $this->identityMap->getObjectByUUID($node->getIdentifier());
		} else {
			$explodedNodeTypeName = explode(':', $node->getPrimaryNodeType()->getName(), 2);
			$className = str_replace('_', '\\', $explodedNodeTypeName[1]);
			$classSchema = $this->persistenceManager->getClassSchema($className);
			$objectConfiguration = $this->objectManager->getObjectConfiguration($className);

			$object = $this->objectBuilder->createEmptyObject($className, $objectConfiguration);
			$this->identityMap->registerObject($object, $node->getIdentifier());

			$properties = array();
			foreach ($classSchema->getProperties() as $propertyName => $propertyType) {
				switch ($propertyType) {
					case 'integer':
					case 'int':
					case 'float':
					case 'boolean':
					case 'string':
					case 'DateTime':
						if ($node->hasProperty('flow3:' . $propertyName)) {
							$property = $node->getProperty('flow3:' . $propertyName);
							$propertyValue = $this->getNativeValue($property->getValue(), $property->getType());
						} else {
							$propertyValue = NULL;
						}
					break;
					case 'array':
						if ($node->hasNode('flow3:' . $propertyName)) {
							$propertyValue = $this->mapArrayProxyNode($node->getNode('flow3:' . $propertyName));
						} else {
							$propertyValue = NULL;
						}
					break;
						// we have an object to handle...
					default:
						if ($node->hasNode('flow3:' . $propertyName)) {
							$propertyNode = $node->getNode('flow3:' . $propertyName);
							if ($propertyNode->getPrimaryNodeType()->getName() === \F3\TYPO3CR\FLOW3\Persistence\Backend::NODETYPE_OBJECTPROXY) {
								$propertyValue = $this->resolveObjectProxyNode($propertyNode);
							} else {
								$propertyValue = $this->mapSingleNode($propertyNode->getNode('flow3:' . $propertyName));
							}
						} else {
							$propertyValue = NULL;
						}
					break;
				}

				$properties[$propertyName] = $propertyValue;
			}

			$this->objectBuilder->reinjectDependencies($object, $objectConfiguration);
			$this->thawProperties($object, $properties);
			$object->memorizeCleanState();
		}

		return $object;
	}

	/**
	 * Sets the given properties on the object.
	 *
	 * @param \F3\FLOW3\AOP\ProxyInterface $object The object to set properties on
	 * @param array $properties The property name/value pairs to set
	 * @return void
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	protected function thawProperties(\F3\FLOW3\AOP\ProxyInterface $object, array $properties) {
		foreach ($properties as $propertyName => $value) {
			$object->AOPProxySetProperty($propertyName, $value);
		}
	}

	/**
	 * Maps an array proxy node back to a native PHP array
	 *
	 * @param NodeInterface $proxyNode
	 * @return array
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @todo remove the check on the node/property names and use name pattern
	 */
	protected function mapArrayProxyNode(\F3\PHPCR\NodeInterface $proxyNode) {
		if ($proxyNode->getPrimaryNodeType()->getName() !== \F3\TYPO3CR\FLOW3\Persistence\Backend::NODETYPE_ARRAYPROXY) {
			throw new \F3\TYPO3CR\FLOW3\Persistence\Exception\UnsupportedTypeException('Arrays can only be mapped back from nodes of type ' . \F3\TYPO3CR\FLOW3\Persistence\Backend::NODETYPE_ARRAYPROXY, 1227705954);
		}
		$array = array();

		$objectNodes = $proxyNode->getNodes();
		foreach ($objectNodes as $objectNode) {
			$objectNodeName = explode(':', $objectNode->getName(), 2);
			if ($objectNode->getPrimaryNodeType()->getName() === \F3\TYPO3CR\FLOW3\Persistence\Backend::NODETYPE_ARRAYPROXY) {
				$array[$objectNodeName[1]] = $this->mapArrayProxyNode($objectNode);
			} elseif ($objectNode->getPrimaryNodeType()->getName() === \F3\TYPO3CR\FLOW3\Persistence\Backend::NODETYPE_OBJECTPROXY) {
				$array[$objectNodeName[1]] = $this->resolveObjectProxyNode($objectNode);
			} elseif ($objectNodeName[0] === 'flow3') {
				$array[$objectNodeName[1]] = $this->mapSingleNode($objectNode);
			}
		}

		$properties = $proxyNode->getProperties();
		foreach ($properties as $property) {
			$propertyName = explode(':', $property->getName(), 2);
			if ($propertyName[0] === 'flow3') {
				$array[$propertyName[1]] = $this->getNativeValue($property->getValue(), $property->getType());
			}
		}

		return $array;
	}

	/**
	 * Fetches the object pointed to by the object proxy node.
	 *
	 * @param \F3\PHPCR\NodeInterface $proxyNode
	 * @return object
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	protected function resolveObjectProxyNode(\F3\PHPCR\NodeInterface $proxyNode) {
		return $this->mapSingleNode($proxyNode->getProperty('flow3:target')->getNode());
	}

	/**
	 * Determines the type of a Value and returns the value as corresponding
	 * native PHP type.
	 *
	 * @param \F3\PHPCR\ValueInterface $value
	 * @param integer $type A constant from \F3\PHPCR\PropertyType
	 * @return mixed
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	protected function getNativeValue(\F3\PHPCR\ValueInterface $value, $type) {
		switch ($type) {
			case \F3\PHPCR\PropertyType::BOOLEAN:
				$value = $value->getBoolean();
				break;
			case \F3\PHPCR\PropertyType::DATE:
				$value = $value->getDate();
				break;
			case \F3\PHPCR\PropertyType::DECIMAL:
			case \F3\PHPCR\PropertyType::DOUBLE:
				$value = $value->getDouble();
				break;
			case \F3\PHPCR\PropertyType::LONG:
				$value = $value->getLong();
				break;
			case \F3\PHPCR\PropertyType::STRING:
				$value = $value->getString();
				break;
			default:
				throw new \F3\TYPO3CR\FLOW3\Persistence\Exception\UnsupportedTypeException('The encountered value type (' . \F3\PHPCR\PropertyType::nameFromValue($type) . ') cannot be mapped.', 1217843827);
				break;
		}

		return $value;
	}
}

?>