<?php

namespace Elgg;

use Elgg\Cli\BaseCommand;
use Exception;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI bootstrap
 */
class Cli {

	use Loggable;

	static $verbosityLevelMap = [
		OutputInterface::VERBOSITY_NORMAL => Logger::WARNING,
		OutputInterface::VERBOSITY_VERBOSE => Logger::NOTICE,
		OutputInterface::VERBOSITY_VERY_VERBOSE => Logger::INFO,
		OutputInterface::VERBOSITY_DEBUG => Logger::DEBUG,
	];

	/**
	 * @var ConsoleApplication
	 */
	protected $console;

	/**
	 * @var HooksRegistrationService
	 */
	protected $hooks;

	/**
	 * @var InputInterface
	 */
	protected $input;

	/**
	 * @var OutputInterface
	 */
	protected $output;

	/**
	 * Constructor
	 *
	 * @param ConsoleApplication $console Console application instance
	 * @param PluginHooksService $hooks   Hooks registration service
	 * @param InputInterface     $input   Console input
	 * @param OutputInterface    $output  Console output
	 */
	public function __construct(
		ConsoleApplication $console,
		PluginHooksService $hooks,
		InputInterface $input,
		OutputInterface $output
	) {
		$this->console = $console;
		$this->hooks = $hooks;
		$this->input = $input;
		$this->output = $output;
	}

	/**
	 * Add CLI tools to the console application
	 * @return void
	 */
	protected function bootstrap() {
		$commands = $this->hooks->trigger('commands', 'cli', null, []);

		foreach ($commands as $command) {
			if (class_exists($command) && is_subclass_of($command, BaseCommand::class)) {
				$command = new $command();
				/* @var $command BaseCommand */

				if ($this->logger) {
					$command->setLogger($this->logger);
				}

				$command->addOption('as', 'u', InputOption::VALUE_OPTIONAL,
					'Execute the command on behalf of a user with the given username'
				);

				$this->console->add($command);
			}
		}
	}

	/**
	 * Bootstrap and run console application
	 *
	 * @return void
	 * @throws Exception
	 */
	public function run() {
		$this->bootstrap();
		$this->console->run($this->input, $this->output);
	}

	/**
	 * Returns console input
	 * @return InputInterface
	 */
	public function getInput() {
		return $this->input;
	}

	/**
	 * Returns console output
	 * @return OutputInterface
	 */
	public function getOutput() {
		return $this->output;
	}
}
