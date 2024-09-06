<?php

namespace Elgg;

use Elgg\Helpers\Upgrade\TestBatch;
use Elgg\Helpers\Upgrade\TestNoIncrementBatch;
use Elgg\Helpers\Upgrade\UnknownSizeTestBatch;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class BatchUpgraderUnitTest extends UnitTestCase {
	
	/**
	 * @var OutputInterface
	 */
	protected $backup_cli_output;
	
	public function up() {
		_elgg_services()->logger->disable();
		
		$this->backup_cli_output = _elgg_services()->get('cli_output');
		
		$cli_output = new NullOutput();
		$cli_output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
		_elgg_services()->set('cli_output', $cli_output);
	}
	
	public function down() {
		_elgg_services()->logger->enable();
		_elgg_services()->set('cli_output', $this->backup_cli_output);
	}

	public function testCanRunIncrementedUpgrade() {
		$upgrade = new \ElggUpgrade();
		$upgrade->setClass(TestBatch::class);
		$upgrade->setId("test_plugin:2016101900");
		$upgrade->title = 'test_plugin:upgrade:2016101900:title';
		$upgrade->description = 'test_plugin:upgrade:2016101900:title';
		$upgrade->access_id = ACCESS_PUBLIC;
		$upgrade->save();

		$upgrader = _elgg_services()->upgrades;
		$result = $upgrader->executeUpgrade($upgrade, 30); // added max_duration to prevent deadloops

		$expected = [
			'errors' => [0, 25, 50, 75],
			'numErrors' => 40,
			'numSuccess' => 60,
			'isComplete' => false,
		];

		$this->assertEquals($expected, $result->toArray());

		$upgrade->delete();
	}

	public function testCanRunIncrementedUpgradeWithInitialOffset() {
		$upgrade = new \ElggUpgrade();
		$upgrade->setClass(TestBatch::class);
		$upgrade->setId("test_plugin:2016101903");
		$upgrade->title = 'test_plugin:upgrade:2016101903:title';
		$upgrade->description = 'test_plugin:upgrade:2016101903:title';
		$upgrade->access_id = ACCESS_PUBLIC;
		$upgrade->save();

		$upgrade->processed = 50;
		$upgrade->offset = 50;
		$upgrade->has_errors = false;

		$upgrader = _elgg_services()->upgrades;
		$result = $upgrader->executeUpgrade($upgrade, 30); // added max_duration to prevent deadloops

		$expected = [
			'errors' => [50, 75],
			'numErrors' => 20,
			'numSuccess' => 30,
			'isComplete' => false,
		];

		$this->assertEquals($expected, $result->toArray());

		$upgrade->delete();
	}

	public function testCanRunUnincrementedUpgrade() {
		$upgrade = new \ElggUpgrade();
		$upgrade->setClass(TestNoIncrementBatch::class);
		$upgrade->setId("test_plugin:2016101901");
		$upgrade->title = 'test_plugin:upgrade:2016101901:title';
		$upgrade->description = 'test_plugin:upgrade:2016101901:title';
		$upgrade->access_id = ACCESS_PUBLIC;
		$upgrade->save();

		$upgrader = _elgg_services()->upgrades;
		$result = $upgrader->executeUpgrade($upgrade, 30); // added max_duration to prevent deadloops

		$expected = [
			'errors' => [0, 10, 20, 30],
			'numErrors' => 40,
			'numSuccess' => 60,
			'isComplete' => false,
		];

		$this->assertEquals($expected, $result->toArray());

		$upgrade->delete();
	}

	public function testCanRunUpgradeWithoutTotal() {
		$upgrade = new \ElggUpgrade();
		$upgrade->setClass(UnknownSizeTestBatch::class);
		$upgrade->setId("test_plugin:2016101902");
		$upgrade->title = 'test_plugin:upgrade:2016101902:title';
		$upgrade->description = 'test_plugin:upgrade:2016101902:title';
		$upgrade->access_id = ACCESS_PUBLIC;
		$upgrade->save();

		$upgrader = _elgg_services()->upgrades;
		$result = $upgrader->executeUpgrade($upgrade, 30); // added max_duration to prevent deadloops

		$expected = [
			'errors' => [],
			'numErrors' => 0,
			'numSuccess' => 20,
			'isComplete' => true,
		];

		$this->assertEquals($expected, $result->toArray());

		$upgrade->delete();
	}
}
