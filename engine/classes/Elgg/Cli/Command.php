<?php

namespace Elgg\Cli;

use RuntimeException;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Abstract command with some utility methods
 */
abstract class Command extends SymfonyCommand {

	use ConsoleInteractions;

	/**
	 * {@inheritdoc}
	 */
	final public function execute(InputInterface $input, OutputInterface $output) {
		$this->input = $input;
		$this->output = $output;

		_elgg_services()->hooks->registerHandler('forward', 'all', [
			$this,
			'dumpRegisters'
		]);
		_elgg_services()->hooks->registerHandler('send:before', 'http_response', [
			$this,
			'dumpData'
		]);

		$this->login();

		$result = $this->command();
		if (is_callable($result)) {
			$result = call_user_func($result, $this);
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
	 * Dump response data
	 *
	 * @param string $event
	 * @param string $type
	 * @param \Symfony\Component\HttpFoundation\Response $response
	 *
	 * @return boolean
	 */
	protected function dumpData($event, $type, $response) {
		$content = $response->getContent();
		$json = @json_decode($content);
		$json ? dump($json) : dump($content);

		return false;
	}

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
		system_message("Logged in as $username [guid: $user->guid]");
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
