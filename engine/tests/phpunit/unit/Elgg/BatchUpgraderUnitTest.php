<?php

namespace Elgg;

use Elgg\Upgrade\TestBatch;
use Elgg\Upgrade\TestNoIncrementBatch;
use Elgg\Upgrade\UnknownSizeTestBatch;
use ElggUpgrade;

/**
 * @group UpgradeService
 */
class BatchUpgraderUnitTest extends UnitTestCase {

	public function setUp() {
		add_subtype('object', 'elgg_upgrade', \ElggUpgrade::class);
	}

	public function testCanRunIncrementedUpgrade() {

		$upgrade = $this->mocks()->getObject([
			'subtype' => 'elgg_upgrade',
			'title' => 'test_plugin:upgrade:2016101900:title',
			'description' => 'test_plugin:upgrade:2016101900:title',
		]);

		$upgrade->setClass(TestBatch::class);
		$upgrade->setId('test_plugin:2016101900');

		$upgrader = new BatchUpgrader(_elgg_config());
		$result = $upgrader->run($upgrade);

		$expected = [
			'errors' => [0, 25, 50, 75],
			'numErrors' => 40,
			'numSuccess' => 60,
			'isComplete' => false,
		];

		$this->assertEquals($expected, $result);
	}

	public function testCanRunIncrementedUpgradeWithInitialOffset() {

		$upgrade = $this->mocks()->getObject([
			'subtype' => 'elgg_upgrade',
			'title' => 'test_plugin:upgrade:2016101900:title',
			'description' => 'test_plugin:upgrade:2016101900:title',
		]);

		$upgrade->setClass(TestBatch::class);
		$upgrade->setId('test_plugin:2016101900');

		$upgrade->processed = 50;
		$upgrade->offset = 50;
		$upgrade->has_errors = false;

		$upgrader = new BatchUpgrader(_elgg_config());
		$result = $upgrader->run($upgrade);

		$expected = [
			'errors' => [50, 75],
			'numErrors' => 20,
			'numSuccess' => 30,
			'isComplete' => false,
		];

		$this->assertEquals($expected, $result);
	}

	public function testCanRunUnincrementedUpgrade() {

		$upgrade = $this->mocks()->getObject([
			'subtype' => 'elgg_upgrade',
			'title' => 'test_plugin:upgrade:2016101901:title',
			'description' => 'test_plugin:upgrade:2016101901:title',
		]);

		$upgrade->setClass(TestNoIncrementBatch::class);
		$upgrade->setId("test_plugin:2016101901");
		
		$upgrader = new BatchUpgrader(_elgg_config());
		$result = $upgrader->run($upgrade);

		$expected = [
			'errors' => [0, 10, 20, 30],
			'numErrors' => 40,
			'numSuccess' => 60,
			'isComplete' => false,
		];

		$this->assertEquals($expected, $result);
	}

	public function testCanRunUpgradeWithoutTotal() {

		$upgrade = $this->mocks()->getObject([
			'subtype' => 'elgg_upgrade',
			'title' => 'test_plugin:upgrade:2016101901:title',
			'description' => 'test_plugin:upgrade:2016101901:title',
		]);

		$upgrade->setClass(UnknownSizeTestBatch::class);
		$upgrade->setId("test_plugin:2016101902");

		$upgrader = new BatchUpgrader(_elgg_config());
		$result = $upgrader->run($upgrade);

		$expected = [
			'errors' => [],
			'numErrors' => 0,
			'numSuccess' => 20,
			'isComplete' => true,
		];

		$this->assertEquals($expected, $result);
	}
}
