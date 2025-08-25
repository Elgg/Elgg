<?php

namespace Elgg\Cli;

use Elgg\Exceptions\RuntimeException;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Abstract command with some utility methods
 */
abstract class Command extends BaseCommand {

	/**
	 * {@inheritdoc}
	 */
	final public function execute(InputInterface $input, OutputInterface $output) {
		$this->input = $input;
		$this->output = $output;

		$transport = new ResponseTransport($this);
		_elgg_services()->responseFactory->setTransport($transport);

		$this->setLanguage();
		$this->login();

		if ($this->option('quiet')) {
			ob_start();
		}
		
		try {
			$result = $this->command();

			if (is_callable($result)) {
				$result = call_user_func($result, $this);
			}
		} catch (\Exception $ex) {
			elgg_log($ex, LogLevel::ERROR);

			$result = $ex->getCode() ?: self::FAILURE;
		}
		
		if ($this->option('quiet')) {
			ob_end_clean();
		}

		$this->dumpRegisters();

		$this->logout();

		return (int) $result;
	}

	/**
	 * Command to be executed
	 *
	 * This method should return an integer code of the error (or 0 for success).
	 * Optionally, the method can return a callable that will receive the instance of this command as an argument
	 *
	 * @return mixed
	 * @see Command::execute()
	 */
	abstract protected function command();

	/**
	 * Login a user defined by --as option
	 *
	 * @return void
	 * @throws RuntimeException
	 */
	final protected function login() {
		if (!$this->getDefinition()->hasOption('as')) {
			return;
		}
		
		$username = $this->option('as');
		if (!$username) {
			return;
		}
		
		$user = elgg_get_user_by_username($username);
		if (!$user) {
			throw new RuntimeException(elgg_echo('user:username:notfound', [$username]));
		}
		
		elgg_login($user);
		
		elgg_log(elgg_echo('cli:login:success:log', [$username, $user->guid]));
	}

	/**
	 * Logout a user
	 * @return void
	 */
	final protected function logout() {
		if (elgg_is_logged_in()) {
			elgg_logout();
		}
	}
	
	/**
	 * Set the language for this cli command
	 *
	 * @return void
	 * @since 3.3
	 */
	final protected function setLanguage() {
		if (!$this->getDefinition()->hasOption('language')) {
			return;
		}
		
		$language = (string) $this->option('language');
		if (empty($language)) {
			return;
		}
		
		_elgg_services()->translator->setCurrentLanguage($language);
	}
}
