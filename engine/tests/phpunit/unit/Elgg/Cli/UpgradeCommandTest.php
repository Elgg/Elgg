<?php

namespace Elgg\Cli;

use Elgg\UnitTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 */
class UpgradeCommandTest extends UnitTestCase {

	public function up() {

		$dbprefix = _elgg_config()->dbprefix;
		_elgg_services()->db->addQuerySpec([
			'sql' => "SHOW TABLES LIKE '{$dbprefix}upgrade_lock'",
			'result' => [],
		]);

		_elgg_services()->db->addQuerySpec([
			'sql' => "CREATE TABLE {$dbprefix}upgrade_lock (id INT)",
			'result' => [],
		]);

		_elgg_services()->db->addQuerySpec([
			'sql' => "DROP TABLE {$dbprefix}upgrade_lock",
			'result' => [],
		]);

		$config = _elgg_services()->dbConfig->getConnectionConfig();
		_elgg_services()->db->addQuerySpec([
			'sql' => "SHOW TABLE STATUS FROM `{$config['database']}`",
			'result' => [],
		]);

	}

	public function down() {

	}

	public function testExecuteWithoutOptions() {
		$application = new Application();
		$application->add(new UpgradeCommand());

		$command = $application->find('upgrade');
		$commandTester = new CommandTester($command);
		$result = $commandTester->execute(['command' => $command->getName()]);

		$this->assertEquals(1, $result);
	}

}
