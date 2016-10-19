<?php

namespace Elgg;

use Elgg\Upgrade\TestBatch;
use Elgg\Upgrade\TestNoIncrementBatch;
use ElggUpgrade;

/**
 * @group UpgradeService
 */
class BatchUpgraderTest extends TestCase {

	public function setUp() {
		$this->setupMockServices();
	}

	public function testCanRunIncrementedUpgrade() {

		$upgrade = new ElggUpgrade();
		$upgrade->setClass(TestBatch::class);
		$upgrade->setId("test_plugin:2016101900");
		$upgrade->title = 'test_plugin:upgrade:2016101900:title';
		$upgrade->description = 'test_plugin:upgrade:2016101900:title';
		$upgrade->save();
		
		$config = _elgg_services()->config;
		$upgrader = new BatchUpgrader($config);
		$result = $upgrader->run($upgrade);

		$expected = [
			'errors' => [0, 25, 50, 75],
			'numErrors' => 40,
			'numSuccess' => 60,
		];

		$this->assertEquals($expected, $result);
	}

	public function testCanRunIncrementedUpgradeWithInitialOffset() {

		$upgrade = new ElggUpgrade();
		$upgrade->setClass(TestBatch::class);
		$upgrade->setId("test_plugin:2016101900");
		$upgrade->title = 'test_plugin:upgrade:2016101900:title';
		$upgrade->description = 'test_plugin:upgrade:2016101900:title';
		$upgrade->save();
		
		$upgrade->processed = 50;
		$upgrade->offset = 50;
		$upgrade->has_errors = false;

		$config = _elgg_services()->config;
		$upgrader = new BatchUpgrader($config);
		$result = $upgrader->run($upgrade);

		$expected = [
			'errors' => [50, 75],
			'numErrors' => 20,
			'numSuccess' => 30,
		];

		$this->assertEquals($expected, $result);
	}

	public function testCanRunUnincrementedUpgrade() {

		$upgrade = new ElggUpgrade();
		$upgrade->setClass(TestNoIncrementBatch::class);
		$upgrade->setId("test_plugin:2016101901");
		$upgrade->title = 'test_plugin:upgrade:2016101901:title';
		$upgrade->description = 'test_plugin:upgrade:2016101901:title';
		$upgrade->save();
		
		$config = _elgg_services()->config;
		$upgrader = new BatchUpgrader($config);
		$result = $upgrader->run($upgrade);

		$expected = [
			'errors' => [0, 10, 20, 30],
			'numErrors' => 40,
			'numSuccess' => 60,
		];

		$this->assertEquals($expected, $result);
	}

}
