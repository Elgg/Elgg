<?php

namespace Elgg\Cli;

use Elgg\IntegrationTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 */
class UpgradeBatchCommandTest extends IntegrationTestCase {

	public function up() {
		self::createApplication([
			'isolate'=> true,
		]);
	}

	public function down() {

	}

	public function testExecuteWithoutOptions() {
		$application = new Application();
		$application->add(new UpgradeBatchCommand());

		$command = $application->find('upgrade:batch');
		$commandTester = new CommandTester($command);
		
		_elgg_services()->logger->disable();
		
		$commandTester->execute([
			'command' => $command->getName(),
			'upgrades' => ['RandomNonExistingClass'],
		]);

		_elgg_services()->logger->enable();
		
		$this->assertStringContainsString(elgg_echo('cli:upgrade:batch:finished'), $commandTester->getDisplay());
	}

	public function testExecuteWithQuietOutput() {
		$application = new Application();
		$application->add(new UpgradeBatchCommand());

		$command = $application->find('upgrade:batch');
		$commandTester = new CommandTester($command);
		
		_elgg_services()->logger->disable();
		
		$commandTester->execute([
			'command' => $command->getName(),
			'upgrades' => ['RandomNonExistingClass'],
			'--quiet' => true,
		]);
		
		_elgg_services()->logger->enable();
		
		$this->assertEmpty($commandTester->getDisplay());
	}
}
