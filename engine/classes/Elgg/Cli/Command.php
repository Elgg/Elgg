<?php

namespace Elgg\Cli;

use RuntimeException;
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

		$this->login();

		try {
			$result = $this->command();

			if (is_callable($result)) {
				$result = call_user_func($result, $this);
			}
		} catch (\Exception $ex) {
			$this->error($ex);

			$result = $ex->getCode() ? : 1;
		}

		$this->dumpRegisters();

		$this->logout();

		return (int) $result;
	}

	/**
	 * Command to be executed
	 *
	 * This method method should return an integer code of the error (or 0 for success).
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
		$user = get_user_by_username($username);
		if (!$user) {
			throw new RuntimeException("User with username $username not found");
		}
		if (!login($user)) {
			throw new RuntimeException("Unable to login as $username");
		}
		elgg_log("Logged in as $username [guid: $user->guid]");
	}

	/**
	 * Logout a user
	 * @return void
	 */
	final protected function logout() {
		if (elgg_is_logged_in()) {
			logout();
		}
	}
}
