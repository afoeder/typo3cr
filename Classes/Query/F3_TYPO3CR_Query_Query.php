<?php
declare(ENCODING = 'utf-8');
namespace F3::TYPO3CR::Query;

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
 * @subpackage Query
 * @version $Id$
 */

/**
 * A Query object.
 *
 * @package TYPO3CR
 * @subpackage Query
 * @version $Id$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 * @scope prototype
 */
class Query implements F3::PHPCR::Query::QueryInterface {

	/**
	 * @var F3::FLOW3::Component::FactoryInterface
	 */
	protected $componentFactory;

	/**
	 * @var F3::TYPO3CR::Storage::BackendInterface
	 */
	protected $storageBackend;

	/**
	 * integer
	 */
	protected $limit;

	/**
	 * integer
	 */
	protected $offset;

	/**
	 * @var string
	 */
	protected $language;

	/**
	 * Injects the Component Factory
	 *
	 * @param F3::FLOW3::Component::FactoryInterface $componentFactory
	 * @return void
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function injectComponentFactory(F3::FLOW3::Component::FactoryInterface $componentFactory) {
		$this->componentFactory = $componentFactory;
	}

	/**
	 * Injects the session for this query
	 *
	 * @param F3::PHPCR::SessionInterface $session
	 * @return void
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function injectSession(F3::PHPCR::SessionInterface $session) {
		$this->storageBackend = $session->getStorageBackend();
	}

	/**
	 * Executes this query and returns a QueryResult object.
	 *
	 * @return F3::PHPCR::Query::QueryInterface a QueryResult object
	 * @throws F3::PHPCR::Query::InvalidQueryException if the query contains an unbound variable.
	 * @throws F3::PHPCR::RepositoryException if an error occurs
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function execute() {
		return $this->componentFactory->create('F3::PHPCR::Query::QueryResultInterface', $this->storageBackend->findNodeIdentifiers($this));
	}

	/**
	 * Sets the maximum size of the result set to limit.
	 *
	 * @param integer $limit
	 * @return void
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function setLimit($limit) {
		if ($limit < 1 || !is_int($limit)) {
			throw new InvalidArgumentException('setLimit() accepts only integers greater than 0.', 1217244746);
		}
		$this->limit = $limit;
	}

	/**
	 * Sets the start offset of the result set to offset.
	 *
	 * @param integer $offset
	 * @return void
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function setOffset($offset) {
		if ($offset < 0 || !is_int($offset)) {
			throw new InvalidArgumentException('setOffset() accepts only integers greater than or equal to 0.', 1217245454);
		}
		$this->offset = $offset;
	}

	/**
	 * Returns the statement defined for this query.
	 * If the language of this query is string-based (like JCR-SQL2), this method
	 * will return the statement that was used to create this query.
	 *
	 * If the language of this query is JCR-JQOM, this method will return the
	 * JCR-SQL2 equivalent of the JCR-JQOM object tree.
	 *
	 * This is the standard serialization of JCR-JQOM and is also the string stored
	 * in the jcr:statement property if the query is persisted. See storeAsNode($absPath).
	 *
	 * @return string the query statement.
	 */
	public function getStatement() {
		throw new F3::PHPCR::UnsupportedRepositoryOperationException('Method not yet implemented, sorry!', 1216897752);
	}

	/**
	 * Returns the language set for this query. This will be one of the query language
	 * constants returned by QueryManager.getSupportedQueryLanguages().
	 *
	 * @return string the query language.
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * If this is a Query object that has been stored using storeAsNode(java.lang.String)
	 * (regardless of whether it has been saved yet) or retrieved using
	 * QueryManager.getQuery(javax.jcr.Node)), then this method returns the path
	 * of the nt:query node that stores the query.
	 *
	 * @return string path of the node representing this query.
	 * @throws F3::PHPCR::ItemNotFoundException if this query is not a stored query.
	 * @throws F3::PHPCR::RepositoryException if another error occurs.
	 */
	public function getStoredQueryPath() {
		throw new F3::PHPCR::UnsupportedRepositoryOperationException('Method not yet implemented, sorry!', 1216897754);
	}

	/**
	 * Creates a node representing this Query in content.
	 *
	 * In a level 2 repository it creates a node of type nt:query at absPath and
	 * returns that node.
	 *
	 * In order to persist the newly created node, a save must be performed that
	 * includes the parent of this new node within its scope. In other words, either
	 * a Session.save or an Item.save on the parent or higher-degree ancestor of
	 * absPath must be performed.
	 *
	 * Strictly speaking, the parameter is actually a absolute path to the parent
	 * node of the node to be added, appended with the name desired for the new node.
	 * It does not specify a position within the child node ordering (if such ordering
	 * is supported). If ordering is supported by the node type of the parent node
	 * then the new node is appended to the end of the child node list.
	 *
	 * @param string $absPath absolute path the query should be stored at
	 * @return F3::PHPCR::NodeInterface the newly created node.
	 * @throws F3::PHPCR::ItemExistsException if an item at the specified path already exists, same-name siblings are not allowed and this implementation performs this validation immediately instead of waiting until save.
	 * @throws F3::PHPCR::PathNotFoundException if the specified path implies intermediary Nodes that do not exist or the last element of relPath has an index, and this implementation performs this validation immediately instead of waiting until save.
	 * @throws F3::PHPCR::NodeType::ConstraintViolationException if a node type or implementation-specific constraint is violated or if an attempt is made to add a node as the child of a property and this implementation performs this validation immediately instead of waiting until save.
	 * @throws F3::PHPCR::Version::VersionException if the node to which the new child is being added is versionable and checked-in or is non-versionable but its nearest versionable ancestor is checked-in and this implementation performs this validation immediately instead of waiting until save.
	 * @throws F3::PHPCR::Lock::LockException if a lock prevents the addition of the node and this implementation performs this validation immediately instead of waiting until save.
	 * @throws F3::PHPCR::UnsupportedRepositoryOperationException in a level 1 implementation.
	 * @throws F3::PHPCR::RepositoryException if another error occurs or if the absPath provided has an index on its final element.
	 */
	public function storeAsNode($absPath) {
		throw new F3::PHPCR::UnsupportedRepositoryOperationException('Method not yet implemented, sorry!', 1216897755);
	}

}

?>