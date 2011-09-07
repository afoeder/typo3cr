<?php
namespace TYPO3\TYPO3CR\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3CR".                    *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Testcase for the "ContenType" domain model
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class ContentTypeTest extends \TYPO3\FLOW3\Tests\UnitTestCase {

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function aContentTypeHasAName() {
		$contentType = new \TYPO3\TYPO3CR\Domain\Model\ContentType('TYPO3.TYPO3:Text');
		$this->assertSame('TYPO3.TYPO3:Text', $contentType->getName());
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function setDeclaredSuperTypesExpectsAnArrayOfContentTypes() {
		$folderType = new \TYPO3\TYPO3CR\Domain\Model\ContentType('TYPO3CR:Folder');
		$folderType->setDeclaredSuperTypes(new \Doctrine\Common\Collections\ArrayCollection(array('foo')));
	}

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function contentTypesCanHaveAnyNumberOfSuperTypes() {
		$baseType = new \TYPO3\TYPO3CR\Domain\Model\ContentType('TYPO3.TYPO3CR:Base');

		$folderType = new \TYPO3\TYPO3CR\Domain\Model\ContentType('TYPO3.TYPO3CR:Folder');
		$folderType->setDeclaredSuperTypes(new \Doctrine\Common\Collections\ArrayCollection(array($baseType)));

		$hideableContentType = new \TYPO3\TYPO3CR\Domain\Model\ContentType('TYPO3.TYPO3:HideableContent');
		$pageType = new \TYPO3\TYPO3CR\Domain\Model\ContentType('TYPO3.TYPO3:Page');

		$pageType->setDeclaredSuperTypes(new \Doctrine\Common\Collections\ArrayCollection(array($folderType, $hideableContentType)));

		$this->assertEquals(array($folderType, $hideableContentType), $pageType->getDeclaredSuperTypes()->toArray());

		$this->assertTrue($pageType->isOfType('TYPO3.TYPO3:Page'));
		$this->assertTrue($pageType->isOfType('TYPO3.TYPO3:HideableContent'));
		$this->assertTrue($pageType->isOfType('TYPO3.TYPO3CR:Folder'));
		$this->assertTrue($pageType->isOfType('TYPO3.TYPO3CR:Base'));
		$this->assertFalse($pageType->isOfType('TYPO3.TYPO3CR:Exotic'));
	}


}