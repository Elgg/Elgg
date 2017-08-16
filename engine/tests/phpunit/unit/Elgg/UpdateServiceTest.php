<?php

namespace Elgg;

/**
 * @group UnitTests
 */
class UpgradeServiceUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var \Elgg\UpgradeService
	 */
	private $service;

	public function up() {

	}

	public function down() {

	}

	/**
	 * This will test an upgrade run, just like calling /upgrade.php
	 */
	public function testUpgradeRun() {
		// marked as incomplete because $CONFIG isn't available
		$this->markTestIncomplete();

		try {
			// running database upgrades can through exceptions
			$result = $this->service->run();

			$this->assertTrue(is_array($result));
			$this->assertArrayHasKey("failure", $result);
			$this->assertFalse($result["failure"]);
		} catch (Exception $e) {
			$this->fail($e->getMessage());
		}
	}

}
