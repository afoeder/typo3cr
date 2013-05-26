<?php
namespace TYPO3\TYPO3CR\ViewHelpers\Widget;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3CR".               *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\Widget\AbstractWidgetViewHelper;
use TYPO3\TYPO3CR\Domain\Model\PersistentNodeInterface;

/**
 * This ViewHelper renders a Pagination of nodes.
 *
 * = Examples =
 *
 * <code title="simple configuration">
 * <typo3cr:widget.paginate parentNode="{parentNode}" as="paginatedNodes" configuration="{itemsPerPage: 5}">
 *   // use {paginatedNodes} inside a <f:for> loop.
 * </typo3cr:widget.paginate>
 * </code>
 *
 * <code title="full configuration">
 * <typo3cr:widget.paginate parentNode="{parentNode}" as="paginatedNodes" nodeTypeFilter="TYPO3.Neos:Page" configuration="{itemsPerPage: 5, insertAbove: 1, insertBelow: 0, maximumNumberOfLinks: 10}">
 *   // use {paginatedNodes} inside a <f:for> loop.
 * </typo3cr:widget.paginate>
 * </code>
 *
 * @api
 */
class PaginateViewHelper extends AbstractWidgetViewHelper {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\ViewHelpers\Widget\Controller\PaginateController
	 */
	protected $controller;

	/**
	 * Render this view helper
	 *
	 * @param \TYPO3\TYPO3CR\Domain\Model\PersistentNodeInterface $parentNode The parent node of the child nodes to show
	 * @param string $as Variable name for the result set
	 * @param string $nodeTypeFilter A node type (or more complex filter) to filter for in the results
	 * @param array $configuration Additional configuration
	 * @return string
	 */
	public function render(PersistentNodeInterface $parentNode, $as, $nodeTypeFilter = NULL, array $configuration = array('itemsPerPage' => 10, 'insertAbove' => FALSE, 'insertBelow' => TRUE, 'maximumNumberOfLinks' => 99)) {
		$response = $this->initiateSubRequest();
		return $response->getContent();
	}
}

?>