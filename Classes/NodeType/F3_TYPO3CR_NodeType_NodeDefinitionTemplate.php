<?php
declare(ENCODING = 'utf-8');

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
 * @subpackage NodeType
 * @version $Id$
 */

/**
 * The NodeDefinitionTemplate interface extends NodeDefinition with the addition
 * of write methods, enabling the characteristics of a child node definition to
 * be set, after which the NodeDefinitionTemplate is added to a NodeTypeTemplate.
 *
 * See the corresponding get methods for each attribute in NodeDefinition for the
 * default values assumed when a new empty NodeDefinitionTemplate is created (as
 * opposed to one extracted from an existing NodeType).
 *
 * @package TYPO3CR
 * @subpackage NodeType
 * @version $Id$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class F3_TYPO3CR_NodeType_NodeDefinitionTemplate extends F3_TYPO3CR_NodeType_NodeDefinition implements F3_PHPCR_NodeType_NodeDefinitionTemplateInterface {

	/**
	 * Sets the name of the node.
	 *
	 * @param string $name a String.
	 * @return void
	 */
	public function setName($name) {
		throw new F3_PHPCR_UnsupportedRepositoryOperationException('Method not yet implemented, sorry!', 1213097323);
	}

	/**
	 * Sets the auto-create status of the node.
	 *
	 * @param boolean $autoCreated a boolean.
	 * @return void
	 */
	public function setAutoCreated($autoCreated) {
		throw new F3_PHPCR_UnsupportedRepositoryOperationException('Method not yet implemented, sorry!', 1213097324);
	}

	/**
	 * Sets the mandatory status of the node.
	 *
	 * @param boolean $mandatory a boolean.
	 * @return void
	 */
	public function setMandatory($mandatory) {
		throw new F3_PHPCR_UnsupportedRepositoryOperationException('Method not yet implemented, sorry!', 1213097325);
	}

	/**
	 * Sets the on-parent-version status of the node.
	 *
	 * @param integer $opv an int constant member of OnParentVersionAction.
	 * @return void
	 */
	public function setOnParentVersion($opv) {
		throw new F3_PHPCR_UnsupportedRepositoryOperationException('Method not yet implemented, sorry!', 1213097326);
	}

	/**
	 * Sets the protected status of the node.
	 *
	 * @param boolean $protectedStatus a boolean.
	 * @return void
	 */
	public function setProtected($protectedStatus) {
		throw new F3_PHPCR_UnsupportedRepositoryOperationException('Method not yet implemented, sorry!', 1213097327);
	}

	/**
	 * Sets the required primary types of this node.
	 *
	 * @param array $requiredPrimaryTypes a String array.
	 * @return void
	 */
	public function setRequiredPrimaryTypes(array $requiredPrimaryTypes) {
		throw new F3_PHPCR_UnsupportedRepositoryOperationException('Method not yet implemented, sorry!', 1213097328);
	}

	/**
	 * Sets the default primary type of this node.
	 *
	 * @param string $defaultPrimaryType a String.
	 * @return void
	 */
	public function setDefaultPrimaryType($defaultPrimaryType) {
		throw new F3_PHPCR_UnsupportedRepositoryOperationException('Method not yet implemented, sorry!', 1213097329);
	}

	/**
	 * Sets the same-name sibling status of this node.
	 *
	 * @param boolean $allowSameNameSiblings a boolean.
	 * @return void
	 */
	public function setSameNameSiblings($allowSameNameSiblings) {
		throw new F3_PHPCR_UnsupportedRepositoryOperationException('Method not yet implemented, sorry!', 1213097330);
	}

}

?>