<?php

namespace Elgg\Cli;

use Elgg\Logger;
use Elgg\UnitTestCase;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 * @group ErrorLog
 */
class CommandTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function executeCommand(\Closure $handler) {
		$command = new TestingCommand();
		$command->setHandler($handler);

		$logger = new Logger('PHPUNIT');

		$output = new NullOutput();
		$output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);

		$handler = new ErrorHandler($output);
		$logger->pushHandler($handler);

		$command->setLogger($logger);

		$application = new Application();
		$application->add($command);

		$command = $application->find('testing');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['command' => $command->getName()]);

		$output = $commandTester->getDisplay();

		return $output;
	}

	public function testCanHandleExceptions() {
		$handler = function(){
			throw new \Exception('Exception thrown');
		};

		$this->assertRegExp('/Exception thrown/im', $this->executeCommand($handler));
	}

	public function testCanLogError() {
		$handler = function(Command $instance) {
			$instance->error('History repeating');
		};

		$this->assertRegExp('/History repeating/im', $this->executeCommand($handler));
	}

	public function testCanLogNotice() {
		$handler = function(Command $instance) {
			$instance->notice('Alexander the Great');
		};

		$this->assertRegExp('/Alexander the Great/im', $this->executeCommand($handler));
	}

	public function testCanRegisterSystemError() {
		$handler = function(Command $instance) {
			register_error('Life is unfair');
		};

		$this->assertRegExp('/Life is unfair/im', $this->executeCommand($handler));
	}

	public function testCanRegisterSystemMessage() {
		$handler = function(Command $instance) {
			system_message('Akuna matata');
		};

		$this->assertRegExp('/Akuna matata/im', $this->executeCommand($handler));
	}
}

class TestingCommand extends Command {

	protected $handler;

	public function setHandler(\Closure $handler) {
		$this->handler = $handler;
	}

	public function configure() {
		$this->setName('testing');
	}

	protected function command() {
		return $this->handler;
	}
}