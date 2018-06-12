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

	}

	public function testExecute() {
		$application = new Application();
		$application->add(new UpgradeCommand());

		$command = $application->find('upgrade');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['command' => $command->getName()]);

		$this->assertRegExp('/System has been upgraded/im', $commandTester->getDisplay());
		$this->assertRegExp('/Plugins have been upgraded/im', $commandTester->getDisplay());
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

		$this->assertRegExp('/System has been upgraded/im', $commandTester->getDisplay());
		$this->assertRegExp('/Plugins have been upgraded/im', $commandTester->getDisplay());
	}

}