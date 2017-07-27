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

		_elgg_services()->hooks->registerHandler('forward', 'all', [$this, 'dumpRegisters']);
		_elgg_services()->hooks->registerHandler('send:before', 'http_response', [$this, 'dumpData']);

		$this->login();

		$result = $this->handle();

		$this->dumpRegisters();

		$this->logout();

		return $result;
	}

	/**
	 * Execute a command
	 * @return int|null
	 * @see Command::execute()
	 */
	abstract protected function handle();

	/**
	 * Dump and output system and error messages
	 * @return void
	 */
	public function dumpRegisters() {
		$set = _elgg_services()->systemMessages->loadRegisters();

		foreach ($set as $prop => $values) {
			if (!empty($values)) {
				foreach ($values as $msg) {
					$tag = $prop == 'error' ? 'error' : 'info';
					$this->write(elgg_format_element($tag, [], $msg));
				}
			}
		}
	}

	/**
	 * Dump response data
	 *
	 * @param string                                     $event
	 * @param string                                     $type
	 * @param \Symfony\Component\HttpFoundation\Response $response
	 *
	 * @return boolean
	 */
	public function dumpData($event, $type, $response) {
		$content = $response->getContent();
		$json = @json_decode($content);
		$json ? dump($json) : dump($content);

		return false;
	}

	/**
	 * Login a user defined by --as option
	 * @return void
	 * @throws RuntimeException
	 */
	final public function login() {
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
	final public function logout() {
		if (elgg_is_logged_in()) {
			logout();
		}
	}

}
