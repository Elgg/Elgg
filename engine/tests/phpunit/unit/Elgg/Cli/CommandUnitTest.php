<?php

namespace Elgg\Cli;

use Elgg\Exceptions\Exception;
use Elgg\Helpers\Cli\TestingCommand;

class CommandUnitTest extends ExecuteCommandUnitTestCase {

	protected function prepareCommand(\Closure $handler): TestingCommand {
		$command = new TestingCommand();
		$command->setHandler($handler);
		
		return $command;
	}
	
	public function testCanHandleExceptions() {
		$command = $this->prepareCommand(function(){
			throw new Exception('Exception thrown');
		});

		$this->assertMatchesRegularExpression('/Exception thrown/im', $this->executeCommand($command));
	}

	public function testCanRegisterSystemError() {
		$command = $this->prepareCommand(function(Command $instance) {
			elgg_register_error_message('Life is unfair');
		});

		$this->assertMatchesRegularExpression('/Life is unfair/im', $this->executeCommand($command));
	}

	public function testCanRegisterSystemMessage() {
		$command = $this->prepareCommand(function(Command $instance) {
			elgg_register_success_message('Akuna matata');
		});

		$this->assertMatchesRegularExpression('/Akuna matata/im', $this->executeCommand($command));
	}
}
