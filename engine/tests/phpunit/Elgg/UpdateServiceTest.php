<?php
namespace Elgg;

class UpgradeServiceTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * @var \Elgg\UpgradeService
	 */
	private $service;
	
	protected function setUp() {
		
		$this->service = new \Elgg\UpgradeService();
	}
	
	protected function tearDown() {
		// @todo should be re-enabled if test is complete
		//$this->service->releaseUpgradeMutex();
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
