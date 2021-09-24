<?php

namespace Elgg\Cli;

use Elgg\IntegrationTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 * @group UpgradeService
 */
class UpgradeCommandTest extends IntegrationTestCase {

	public function up() {

	}

	public function down() {
		if (_elgg_services()->mutex->isLocked('upgrade')) {
			_elgg_services()->mutex->unlock('upgrade');
		}
	}

	public function testExecute() {
		$application = new Application();
		$application->add(new UpgradeCommand());

		$command = $application->find('upgrade');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['command' => $command->getName()]);

		$this->assertStringContainsStringIgnoringCase(elgg_echo('cli:upgrade:system:upgraded'), $commandTester->getDisplay());
		$this->assertEmpty($commandTester->getStatusCode());
	}
	
	public function testExecuteFailsWhenLocked() {
		_elgg_services()->mutex->lock('upgrade');
		
		$application = new Application();
		$application->add(new UpgradeCommand());
		
		$command = $application->find('upgrade');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['command' => $command->getName()]);
		
		$this->assertNotEmpty($commandTester->getStatusCode());
	}
	
	public function testExecuteForceWhenLocked() {
		_elgg_services()->mutex->lock('upgrade');
		
		$application = new Application();
		$application->add(new UpgradeCommand());
		
		$command = $application->find('upgrade');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--force' => true,
		]);
		
		$this->assertStringContainsStringIgnoringCase(elgg_echo('cli:upgrade:system:upgraded'), $commandTester->getDisplay());
		$this->assertEmpty($commandTester->getStatusCode());
	}

	public function testExecuteAsyncUpgrades() {
		$application = new Application();
		$application->add(new UpgradeCommand());

		$command = $application->find('upgrade');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'async' => ['async'],
			'--quiet' => true,
		]);

		$this->assertStringContainsStringIgnoringCase(elgg_echo('cli:upgrade:system:upgraded'), $commandTester->getDisplay());
		$this->assertStringContainsStringIgnoringCase(elgg_echo('cli:upgrade:async:upgraded'), $commandTester->getDisplay());
		$this->assertEmpty($commandTester->getStatusCode());
	}
}
