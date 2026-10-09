<?php

namespace Elgg\Upgrade;

use Elgg\Helpers\Upgrade\UpgradeLocatorTestBatch;
use Elgg\IntegrationTestCase;

class LocatorIntegrationTest extends IntegrationTestCase {
	
	protected ?\ElggUpgrade $upgrade_entity = null;
	
	public function up() {
		$batch = new UpgradeLocatorTestBatch();
		$version = $batch->getVersion();

		$upgrade = new \ElggUpgrade();
		$upgrade->class = UpgradeLocatorTestBatch::class;
		$upgrade->id = "test_plugin:{$version}";
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($upgrade) {
			$upgrade->save();
		});
		
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
