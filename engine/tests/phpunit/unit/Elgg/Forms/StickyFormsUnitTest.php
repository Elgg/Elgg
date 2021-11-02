<?php

namespace Elgg\Forms;

/**
 * @group UnitTests
 */
class StickyFormsUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var StickyForms
	 */
	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->stickyForms;
	}

	public function testIsStickyReturnsTrueForFormsMarkedAsSticky() {
		$this->assertFalse($this->service->isStickyForm('foo'));
		
		$this->service->makeStickyForm('foo');
		$this->assertTrue($this->service->isStickyForm('foo'));
	}

	public function testIsStickyReturnsFalseForClearedStickyForms() {
		$this->service->makeStickyForm('foo');
		$this->assertTrue($this->service->isStickyForm('foo'));
		
		$this->service->clearStickyForm('foo');
		$this->assertFalse($this->service->isStickyForm('foo'));
	}
}
