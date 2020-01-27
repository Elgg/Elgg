<?php

namespace Elgg;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

class LocatorIntegrationTest extends IntegrationTestCase {
	
	/**
	 * @var \ElggUpgrade
	 */
	protected $upgrade_entity;
	
	public function up() {
		
		$batch = new UpgradeLocatorTestBatch();
		$version = $batch->getVersion();

		$upgrade = new \ElggUpgrade();
		$upgrade->setClass(UpgradeLocatorTestBatch::class);
		$upgrade->setId("test_plugin:$version");
		$upgrade->title = "test_plugin:upgrade:$version:title";
		$upgrade->description = "test_plugin:upgrade:$version:title";
		$upgrade->access_id = ACCESS_PUBLIC;
		$upgrade->save();
		
		$this->upgrade_entity = $upgrade;
	}
	
	public function down() {
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$this->upgrade_entity->delete();
		});
	}

	public function testCanGetExistingUpgradeFromId() {
		$found_entity = _elgg_services()->upgradeLocator->upgradeExists($this->upgrade_entity->id);
		$this->assertInstanceOf(\ElggUpgrade::class, $found_entity);
		$this->assertEquals($this->upgrade_entity->guid, $found_entity->guid);
	}

	public function testCanGetExistingUpgradeByClass() {
		$found_entity = _elgg_services()->upgradeLocator->getUpgradeByClass(UpgradeLocatorTestBatch::class);
		$this->assertInstanceOf(\ElggUpgrade::class, $found_entity);
		$this->assertEquals($this->upgrade_entity->guid, $found_entity->guid);
	}
}

class UpgradeLocatorTestBatch implements AsynchronousUpgrade {

	protected $_version;

	public function getVersion() {
		if (!isset($this->_version)) {
			$this->_version = date('Ymd') . rand(10, 99);
		}

		return $this->_version;
	}

	public function needsIncrementOffset() {
		return true;
	}

	public function shouldBeSkipped() {
		return false;
	}

	public function countItems() {
		return 100;
	}

	public function run(Result $result, $offset) {
		$result->addSuccesses(10);
	}
}
