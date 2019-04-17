<?php

namespace Elgg;

use Elgg\Cli\Application as CliApplication;
use Elgg\Cli\BaseCommand;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI bootstrap
 */
class Cli {

	use Loggable;

	/**
	 * @var CliApplication
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
	 * @param CliApplication     $console Console application instance
	 * @param PluginHooksService $hooks   Hooks registration service
	 * @param InputInterface     $input   Console input
	 * @param OutputInterface    $output  Console output
	 */
	public function __construct(
		CliApplication $console,
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
			$this->add($command);
		}
	}

	/**
	 * Add a new CLI command
	 *
	 * @param string $command Command class
	 *                        Must extend \Elgg\Cli\BaseCommand
	 *
	 * @return void
	 */
	public function add($command) {
		if (!class_exists($command)) {
			return;
		}

		if (!is_subclass_of($command, BaseCommand::class)) {
			return;
		}

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
