<?php

namespace Elgg\Cli;

use Elgg\UnitTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 * @group Cron
 */
class CronCommandTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testExecuteWithoutOptions() {
		$application = new Application();
		$application->add(new CronCommand());

		$command = $application->find('cron');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['command' => $command->getName()]);

		$this->assertMatchesRegularExpression('/Cron jobs for .* started/im', $commandTester->getDisplay());
		$this->assertMatchesRegularExpression('/Cron jobs for .* completed/im', $commandTester->getDisplay());
	}

	public function testExecuteWithPeriod() {
		$application = new Application();
		$application->add(new CronCommand());

		$command = $application->find('cron');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--interval' => "hourly",
			'--time' => '2017-12-31 0:00:00',
		]);

		$this->assertMatchesRegularExpression('/Cron jobs for \"hourly\" started/im', $commandTester->getDisplay());
		$this->assertMatchesRegularExpression('/Cron jobs for \"hourly\" completed/im', $commandTester->getDisplay());
	}

	public function testExecuteWithQuietOutput() {
		$application = new Application();
		$application->add(new CronCommand());

		$command = $application->find('cron');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--quiet' => true,
		]);

		$this->assertEmpty($commandTester->getDisplay());
	}

}