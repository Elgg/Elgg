<?php

namespace Elgg\Cli;

use Elgg\GarbageCollector\OptimizeCommand;
use Elgg\IntegrationTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 * @group GarbageCollector
 */
class OptimizeCommandTest extends IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testExecute() {
		$application = new Application();
		$application->add(new OptimizeCommand());

		$command = $application->find('database:optimize');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
		]);

		$this->assertStringContainsStringIgnoringCase(elgg_echo('garbagecollector:done'), $commandTester->getDisplay());
	}
}
