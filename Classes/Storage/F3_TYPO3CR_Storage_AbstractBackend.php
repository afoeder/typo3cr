<?php
declare(ENCODING = 'utf-8');
namespace F3::TYPO3CR::Storage;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * @package TYPO3CR
 * @subpackage Storage
 * @version $Id$
 */

/**
 * An abstract storage backend
 *
 * @package TYPO3CR
 * @subpackage Storage
 * @version $Id:F3::FLOW3::AOP::Framework.php 201 2007-03-30 11:18:30Z robert $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 * @scope prototype
 */
abstract class AbstractBackend implements F3::TYPO3CR::Storage::BackendInterface {

	/**
	 * @var string Name of the current workspace
	 */
	protected $workspaceName = 'default';

	/**
	 * @var F3::TYPO3CR::Storage::SearchInterface
	 */
	protected $searchEngine;

	/**
	 * @var F3::TYPO3CR::NamespaceRegistryInterface
	 */
	protected $namespaceRegistry;

	/**
	 * Constructs this backend
	 *
	 * @param mixed $options Configuration options - depends on the actual backend
	 */
	public function __construct($options = array()) {
		foreach ($options as $optionKey => $optionValue) {
			$methodName = 'set' . ucfirst($optionKey);
			if (method_exists($this, $methodName)) {
				$this->$methodName($optionValue);
			}
		}
	}

	/**
	 * Sets the name of the current workspace
	 *
	 * @param  string $workspaceName Name of the workspace which should be used for all storage operations
	 * @return void
	 * @throws InvalidArgumentException
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function setWorkspaceName($workspaceName) {
		if ($workspaceName == '' || !is_string($workspaceName)) throw new InvalidArgumentException('"' . $workspaceName . '" is not a valid workspace name.', 1200614989);
		$this->workspaceName = $workspaceName;
		$this->searchEngine->setWorkspaceName($workspaceName);
	}

	/**
	 * Sets the search engine used by the storage backend.
	 *
	 * @param F3::TYPO3CR::Storage::SearchInterface $searchEngine
	 * @return void
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function setSearchEngine(F3::TYPO3CR::Storage::SearchInterface $searchEngine) {
		$this->searchEngine = $searchEngine;
	}

	/**
	 * Sets the namespace registry used by the storage backend
	 *
	 * @param F3::PHPCR::NamespaceRegistryInterface $namespaceRegistry
	 * @return void
	 * @author Matthias Hoermann <hoermann@saltation.de>
	 */
	public function setNamespaceRegistry(F3::PHPCR::NamespaceRegistryInterface $namespaceRegistry) {
		$this->namespaceRegistry = $namespaceRegistry;
	}


}
?>