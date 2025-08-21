<?php

namespace Elgg\Plugins;

use Elgg\BaseIntegrationTestCase;
use Elgg\Cli\Application;
use Elgg\Logger;
use Monolog\Handler\TestHandler;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Extend this class if you wish to run integration tests in your plugin.
 * It will skip the tests of your plugin in not active.
 *
 * DO NOT RUN ON PRODUCTION
 */
abstract class IntegrationTestCase extends BaseIntegrationTestCase {
	
	protected ?OutputInterface $backup_output;
	
	protected ?Logger $backup_logger;
	
	/**
	 * {@inheritdoc}
	 */
	final protected function setUp(): void {
		parent::setUp();
		
		$plugin_id = $this->getPluginID();
		if (!empty($plugin_id)) {
			$plugin = elgg_get_plugin_from_id($plugin_id);
			
			if (!$plugin || !$plugin->isActive()) {
				$this->markTestSkipped("Plugin '{$plugin_id}' isn't active, skipped test");
			}
		}
		
		$this->up();
	}
	
	public function down() {
		if (isset($this->backup_output)) {
			_elgg_services()->set('cli_output', $this->backup_output);
		}
		
		if (isset($this->backup_logger)) {
			_elgg_services()->set('logger', $this->backup_logger);
		}
		parent::down();
	}
	
	protected function executeCliCommand(SymfonyCommand $command, array $input = [], array $options = [], bool $status_code = false): int|string {
		$this->backup_output = _elgg_services()->cli_output;
		
		$cli_output = new BufferedOutput(OutputInterface::VERBOSITY_VERY_VERBOSE);
		_elgg_services()->set('cli_output', $cli_output);
		
		$this->backup_logger = _elgg_services()->logger;
		
		$logger = new Logger('PHPUNIT');
		
		$log_handler = new TestHandler();
		$logger->pushHandler($log_handler);
		
		_elgg_services()->set('logger', $logger);
		
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
		$result .= $cli_output->fetch();
		
		$records = $log_handler->getRecords();
		foreach ($records as $record) {
			$result .= $record->message;
		}
		
		_elgg_services()->set('cli_output', $this->backup_output);
		unset($this->backup_output);
		
		_elgg_services()->set('logger', $this->backup_logger);
		unset($this->backup_logger);
		
		return $result;
	}
}
