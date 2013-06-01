<?php

class ElggTravisInstallTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {

		if (!getenv('TRAVIS')) {
			$this->markTestSkipped("Not Travis VM");
		}
	}

	public function testDBInstall() {
		_elgg_services()->db->assertInstalled();
	}
}
