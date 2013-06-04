<?php

class ElggTravisInstallTest extends ElggCoreUnitTest {

	public function setUp() {

		if (!getenv('TRAVIS')) {
			$this->skipIf(true, "Not Travis VM");
		}
	}

	public function testDBInstall() {
		_elgg_services()->db->assertInstalled();
	}
}
