<?php

namespace Elgg\Cli;

use Elgg\IntegrationTestCase;
use Elgg\Logger;
use Monolog\Handler\TestHandler;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

class ExecuteCommandIntegrationTestCase extends IntegrationTestCase {
	
	protected ?BufferedOutput $cli_output;
	
	protected ?TestHandler $log_handler;
	
	public function up() {
		parent::up();
		
		$this->setupApplication();
	}
	
	protected function setupApplication(array $params = []): void {
		$params['isolate'] = true;
		
		$this->createApplication($params);
		
		$this->cli_output = new BufferedOutput(OutputInterface::VERBOSITY_VERY_VERBOSE);
		
		$logger = new Logger('PHPUNIT');
		
		$this->log_handler = new TestHandler();
		$logger->pushHandler($this->log_handler);
		
		_elgg_services()->set('cli_output', $this->cli_output);
		_elgg_services()->set('logger', $logger);
	}
	
	protected function executeCommand(SymfonyCommand $command, array $input = [], array $options = [], bool $status_code = false): int|string {
		$application = new Application();
		$application->setup(_elgg_services()->cli_input, _elgg_services()->cli_output);
		$application->add($command);
		
		$commandTester = new CommandTester($command);
		
		$input['command'] = $command->getName();
		
		if (!isset($input['--quiet'])) {
			$options['verbosity'] = OutputInterface::VERBOSITY_VERY_VERBOSE;
		} else {
			$options['verbosity'] = OutputInterface::VERBOSITY_QUIET;
			_elgg_services()->cli_output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
		}
		
		$status = $commandTester->execute($input, $options);
		if ($status_code) {
			return $status;
		}
		
		$result = $commandTester->getDisplay();
		$result .= $this->cli_output->fetch();
		
		$records = $this->log_handler->getRecords();
		foreach ($records as $record) {
			$result .= $record->message;
		}
		
		return $result;
	}
}
