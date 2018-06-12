<?php

namespace Elgg\Cli;

use Elgg\IntegrationTestCase;
use Elgg\Logger;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 */
class FlushCommandTest extends IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testExecute() {
		$application = new Application();
		$application->add(new FlushCommand());

		$command = $application->find('flush');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['command' => $command->getName()]);

		$this->assertRegExp('/System caches have been flushed/im', $commandTester->getDisplay());
	}

}