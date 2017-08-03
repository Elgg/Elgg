<?php

class ElggTravisInstallTest extends \ElggCoreUnitTest {

	public function setUp() {
		if (!getenv('TRAVIS')) {
			$this->skipIf(true, "Not Travis VM");
		}
	}

	public function testDbWasInstalled() {
		$this->assertNotNull(_elgg_services()->configTable->get('installed'));
	}
}
