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

	public function testCanLogError() {
		$command = $this->prepareCommand(function(Command $instance) {
			$instance->error('History repeating');
		});

		$this->assertMatchesRegularExpression('/History repeating/im', $this->executeCommand($command));
	}

	public function testCanLogNotice() {
		$command = $this->prepareCommand(function(Command $instance) {
			$instance->notice('Alexander the Great');
		});

		$this->assertMatchesRegularExpression('/Alexander the Great/im', $this->executeCommand($command));
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
