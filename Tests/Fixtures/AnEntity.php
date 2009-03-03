<?php
declare(ENCODING = 'utf-8');
namespace F3\TYPO3CR\Tests\Fixtures;

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
 * @subpackage Tests
 * @version $Id$
 */

/**
 * Fixture class for Persistence\BackendTest->complexObjectsAreStoredCorrectly()
 *
 * @package TYPO3CR
 * @subpackage Tests
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @entity
 */
class AnEntity implements \F3\FLOW3\AOP\ProxyInterface, \F3\FLOW3\Persistence\Aspect\DirtyMonitoringInterface {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $members;

	/**
	 * @var \F3\TYPO3CR\Tests\Fixtures\AValue
	 */
	protected $value;

	/**
	 * @param string $name
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * @param object $object
	 * @return void
	 */
	public function add($object) {
		$this->members[] = $object;
	}

	/**
	 * @param \F3\TYPO3CR\Tests\Fixtures\AValue $value
	 * @return void
	 */
	public function setValue(\F3\TYPO3CR\Tests\Fixtures\AValue $value) {
		$this->value = $value;
	}

	public function isNew() {
		return TRUE;
	}

	public function isDirty($propertyName) {
		return FALSE;
	}

	public function memorizeCleanState(\F3\FLOW3\AOP\JoinPointInterface $joinPoint = NULL) {
	}

	/**
	 * Returns the name of the class this proxy extends.
	 *
	 * @return string Name of the target class
	 */
	public function AOPProxyGetProxyTargetClassName() {
		return get_class($this);
	}

	/**
	 * Invokes the joinpoint - calls the target methods.
	 *
	 * @param \F3\FLOW3\AOP\JoinPointInterface: The join point
	 * @return mixed Result of the target (ie. original) method
	 */
	public function AOPProxyInvokeJoinPoint(\F3\FLOW3\AOP\JoinPointInterface $joinPoint) {

	}

	/**
	 * Returns the value of an arbitrary property.
	 * The method does not have to check if the property exists.
	 *
	 * @param string $propertyName Name of the property
	 * @return mixed Value of the property
	 */
	public function AOPProxyGetProperty($propertyName) {
		return $this->$propertyName;
	}

	/**
	 * Sets the value of an arbitrary property.
	 *
	 * @param string $propertyName Name of the property
	 * @param mixed $propertyValue Value to set
	 * @return void
	 */
	public function AOPProxySetProperty($propertyName, $propertyValue) {

	}

}

?>