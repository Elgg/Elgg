<?php

namespace Elgg\Cli;

use Elgg\Application;
use Elgg\Logger;
use Error;
use Exception;
use RuntimeException;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\FormatterHelper;
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

		Application::getContainer()->kernel->setErrorHandler([$this, 'handleErrors']);
		Application::getContainer()->kernel->setExceptionHandler([$this, 'handleException']);

		Logger::$verbosity = $output->getVerbosity();

		$this->input = $input;
		$this->output = $output;

		elgg_set_config('debug', 'INFO');

		_elgg_services()->responseFactory->setTransport(new ResponseTransport($this));

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

	/**
	 * Handler errors
	 *
	 * @param int    $errno    The level of the error raised
	 * @param string $errmsg   The error message
	 * @param string $filename The filename the error was raised in
	 * @param int    $linenum  The line number the error was raised at
	 * @param array  $vars     An array that points to the active symbol table where error occurred
	 *
	 * @return true
	 * @throws \Exception
	 * @access private
	 */
	public function handleErrors($errno, $errmsg, $filename, $linenum, $vars) {
		$error = date("Y-m-d H:i:s (T)") . ": \"$errmsg\" in file $filename (line $linenum)";

		switch ($errno) {
			case E_USER_ERROR:
				throw new Exception($error);

			default:
				$formatter = new FormatterHelper();
				$message = $formatter->formatBlock($error, 'error');
				$this->output->writeln($message);
				break;
		}

		return true;
	}

	/**
	 * Handle exceptions
	 *
	 * @param Exception|Error $exception
	 *
	 * @return void
	 * @throws Exception
	 */
	public function handleException($exception) {
		$this->setCode(function () use ($exception) {
			return $exception->getCode() ? : 1;
		});

		$timestamp = time();
		$error = "Exception at time $timestamp: $exception";

		$formatter = new FormatterHelper();
		$message = $formatter->formatBlock($error, 'error');
		$this->output->writeln($message);
	}

}
