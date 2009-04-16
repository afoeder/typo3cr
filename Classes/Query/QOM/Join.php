<?php
declare(ENCODING = 'utf-8');
namespace F3\TYPO3CR\Query\QOM;

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
 * @subpackage Query
 * @version $Id$
 */

/**
 * Performs a join between two node-tuple sources.
 *
 * @package TYPO3CR
 * @subpackage Query
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
class Join implements \F3\PHPCR\Query\QOM\JoinInterface {

	/**
	 * @var \F3\PHPCR\Query\QOM\SourceInterface
	 */
	protected $left;

	/**
	 * @var \F3\PHPCR\Query\QOM\SourceInterface
	 */
	protected $right;

	/**
	 * @var integer
	 */
	protected $joinType;

	/**
	 * @var \F3\PHPCR\Query\QOM\JoinConditionInterface
	 */
	protected $joinCondition;

	/**
	 * Constructs the Join instance
	 *
	 * @param \F3\PHPCR\Query\QOM\SourceInterface $left the left node-tuple source; non-null
	 * @param \F3\PHPCR\Query\QOM\SourceInterface $right the right node-tuple source; non-null
	 * @param integer $joinType either QueryObjectModelConstants.JOIN_TYPE_INNER, QueryObjectModelConstants.JOIN_TYPE_LEFT_OUTER, QueryObjectModelConstants.JOIN_TYPE_RIGHT_OUTER
	 * @param \F3\PHPCR\Query\QOM\JoinConditionInterface $join Condition the join condition; non-null
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function __construct(\F3\PHPCR\Query\QOM\SourceInterface $left, \F3\PHPCR\Query\QOM\SourceInterface $right, $joinType, \F3\PHPCR\Query\QOM\JoinConditionInterface $joinCondition) {
		$this->left = $left;
		$this->right = $right;
		$this->joinType = $joinType;
		$this->joinCondition = $joinCondition;
	}

	/**
	 * Gets the left node-tuple source.
	 *
	 * @return \F3\PHPCR\Query\QOM\SourceInterface the left source; non-null
	 */
	public function getLeft() {
		return $this->left;
	}

	/**
	 * Gets the right node-tuple source.
	 *
	 * @return \F3\PHPCR\Query\QOM\SourceInterface the right source; non-null
	 */
	public function getRight() {
		return $this->right;
	}

	/**
	 * Gets the join type.
	 *
	 * @return integer one of QueryObjectModelConstants.JOIN_TYPE_*
	 */
	public function getJoinType() {
		return $this->joinType;
	}

	/**
	 * Gets the join condition.
	 *
	 * @return JoinCondition the join condition; non-null
	 */
	public function getJoinCondition() {
		return $this->joinCondition;
	}

}

?>