<?php

namespace Elgg;

use Elgg\Cli\Application as CliApplication;
use Elgg\Cli\BaseCommand;
use Elgg\Cli\Command;
use Elgg\Traits\Loggable;
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
	 * @var PluginHooksService
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
		$commands = array_merge($this->getCoreCommands(), $this->getPluginCommands());
		$commands = $this->hooks->trigger('commands', 'cli', null, $commands);

		foreach ($commands as $command) {
			$this->add($command);
		}
	}

	/**
	 * Returns the core cli commands
	 * @return array
	 */
	protected function getCoreCommands() {
		$conf = \Elgg\Project\Paths::elgg() . 'engine/cli_commands.php';
		return \Elgg\Includer::includeFile($conf);
	}

	/**
	 * Returns the cli commands registered in plugins
	 * @return array
	 */
	protected function getPluginCommands() {
		$return = [];
		
		$plugins = elgg_get_plugins('active');
		foreach ($plugins as $plugin) {
			$plugin_commands = $plugin->getStaticConfig('cli_commands', []);
			if (empty($plugin_commands)) {
				continue;
			}
			
			$return = array_merge($return, $plugin_commands);
		}
		
		return $return;
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

		$command->setLogger($this->getLogger());
		
		if (!is_subclass_of($command, Command::class)) {
			$this->console->add($command);
			return;
		}

		// Execute command as a given user
		$command->addOption('as', 'u', InputOption::VALUE_OPTIONAL,
			elgg_echo('cli:option:as')
		);
		
		// Change language
		$command->addOption('language', null, InputOption::VALUE_OPTIONAL,
			elgg_echo('cli:option:language')
		);

		$this->console->add($command);
	}

	/**
	 * Bootstrap and run console application
	 *
	 * @param bool $bootstrap Is bootstrap needed?
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function run(bool $bootstrap = true) {
		if ($bootstrap) {
			$this->bootstrap();
		}
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
