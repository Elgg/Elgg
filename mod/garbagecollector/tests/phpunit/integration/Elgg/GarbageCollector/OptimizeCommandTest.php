<?php

namespace Elgg\GarbageCollector;

use Elgg\Plugins\IntegrationTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class OptimizeCommandTest extends IntegrationTestCase {

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
