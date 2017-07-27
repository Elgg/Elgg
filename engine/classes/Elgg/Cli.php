<?php

namespace Elgg;

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Command\Command;

/**
 * CLI bootstrap
 */
class Cli {

	/**
	 * @var ConsoleApplication
	 */
	protected $console;

	/**
	 * @var HooksRegistrationService
	 */
	protected $hooks;

	/**
	 * Constructor
	 *
	 * @param ConsoleApplication       $console         Console application instance
	 * @param HooksRegistrationService $hooks           Hooks registration service
	 */
	public function __construct(ConsoleApplication $console, HooksRegistrationService $hooks) {
		$this->console = $console;
		$this->hooks = $hooks;
	}

	/**
	 * Add CLI tools to the console application
	 * @return void
	 */
	protected function bootstrap() {
		$commands = $this->hooks->trigger('commands', 'cli', null, []);
		foreach ($commands as $command) {
			if (class_exists($command) && is_subclass_of($command, Command::class)) {
				$this->console->add(new $command());
			}
		}
	}

	/**
	 * Bootstrap and run console application
	 * @return void
	 */
	public function run() {
		$this->bootstrap();
		$this->console->run();
	}

}
