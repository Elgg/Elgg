<?php

namespace Elgg\Cli;

use Elgg\IntegrationTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 */
class CacheInvalidateCommandTest extends IntegrationTestCase {

	public function up() {
		self::createApplication([
			'isolate'=> true,
		]);
	}

	public function down() {

	}

	public function testExecuteWithoutOptions() {
		$application = new Application();
		$application->add(new CacheInvalidateCommand());

		$command = $application->find('cache:invalidate');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['command' => $command->getName()]);

		$this->assertMatchesRegularExpression('/' . elgg_echo('admin:cache:invalidated') . '/im', $commandTester->getDisplay());
	}

	public function testExecuteWithQuietOutput() {
		$application = new Application();
		$application->add(new CacheInvalidateCommand());

		$command = $application->find('cache:invalidate');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--quiet' => true,
		]);
		
		$this->assertEmpty($commandTester->getDisplay());
	}
}
