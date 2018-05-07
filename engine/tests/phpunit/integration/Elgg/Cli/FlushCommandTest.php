<?php

namespace Elgg\Cli;

use Elgg\IntegrationTestCase;
use Elgg\Logger;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
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
		$command = new FlushCommand();

		$logger = new Logger('PHPUNIT');

		$output = new NullOutput();
		$output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);

		$handler = new ErrorHandler($output);
		$logger->pushHandler($handler);

		$command->setLogger($logger);

		$application = new Application();
		$application->add($command);

		$command = $application->find('flush');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['command' => $command->getName()]);

		$this->assertRegExp('/System caches have been flushed/im', $commandTester->getDisplay());
	}

}