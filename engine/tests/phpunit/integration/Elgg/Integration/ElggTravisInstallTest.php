<?php

namespace Elgg\Integration;

class ElggTravisInstallTest extends \Elgg\LegacyIntegrationTestCase {

	public function up() {
		if (!getenv('TRAVIS')) {
			$this->skipIf(true, "Not Travis VM");
		}
	}

	public function down() {

	}

	public function testDbWasInstalled() {
		$this->assertNotNull(_elgg_services()->configTable->get('installed'));
	}
}
