<?php

namespace Elgg\Integration;

class ElggTravisInstallTest extends \Elgg\IntegrationTestCase {

	public function up() {
		if (!getenv('TRAVIS')) {
			$this->markTestSkipped("Not Travis VM");
		}
	}

	public function down() {

	}

	public function testDbWasInstalled() {
		$this->assertNotNull(_elgg_services()->configTable->get('installed'));
	}
}
