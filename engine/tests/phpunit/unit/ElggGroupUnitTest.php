<?php

class ElggGroupUnitTest extends \Elgg\UnitTestCase {

	protected function setUp() {
		// required by \ElggEntity when setting the owner/container
		_elgg_services()->setValue('session', \ElggSession::getMock());
	}

	public function testCanConstructWithoutArguments() {
		$this->assertNotNull(new \ElggGroup());
	}

}
