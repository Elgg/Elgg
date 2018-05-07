<?php

namespace Elgg\Cli;

use Elgg\Hook;
use Elgg\IntegrationTestCase;
use Elgg\Logger;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 */
class SimpleTestCommandTest extends IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testExecute() {

		$hook = $this->registerTestingHook('unit_test', 'system', function (Hook $hook) {
			return [
				CliSimpletest::class,
			];
		});

		$command = new SimpletestCommand();

		$logger = new Logger('PHPUNIT');

		$output = new NullOutput();
		$output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);

		$handler = new ErrorHandler($output);
		$logger->pushHandler($handler);

		$command->setLogger($logger);

		$application = new Application();
		$application->add($command);

		$command = $application->find('simpletest');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--quiet' => true,
		]);

		$class = preg_quote(CliSimpletest::class);
		$this->assertRegExp("/{$class}::testMe/im", $commandTester->getDisplay());

		$hook->unregister();
	}

}

class CliSimpletest extends \ElggCoreUnitTest {

	public function up() {

	}

	public function down() {

	}

	public function testMe() {
		system_message(__METHOD__);
	}
}