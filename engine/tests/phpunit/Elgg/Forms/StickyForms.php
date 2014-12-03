<?php
namespace Elgg\Forms;

use PHPUnit_Framework_TestCase as TestCase;

class StickyFormsTest extends TestCase {
	public function testIsStickyReturnsTrueForFormsMarkedAsSticky() {
		$this->markTestIncomplete();
	}
	
	public function testIsStickyReturnsFalseForClearedStickyForms() {
		$this->markTestIncomplete();
	}
	
	/**
	 * It's important to test that this information is actually stored in the
	 * session because that is a meaningful implementation detail that guarantees
	 * the "sticky" aspect. If it just stored inputs it in an array cache,
	 * the information would be lost at the end of the request (i.e. not sticky).
	 */
	public function testMakeStickyFormStoresInputsInTheSession() {
		$this->markTestIncomplete();
	}
}