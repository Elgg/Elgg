<?php
/**
 *
 */

namespace Elgg\Cli;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Adds interaction to a console command
 */
trait ConsoleInteractions {

	/**
	 * @var InputInterface
	 */
	protected $input;

	/**
	 * @var OutputInterface
	 */
	protected $output;

	/**
	 * Ask a question
	 *
	 * @param string $question Question to ask
	 * @param mixed  $default  Default value
	 * @param bool   $hidden   Hide response
	 * @param bool   $required User input is required
	 *
	 * @return mixed
	 */
	public function ask($question, $default = null, $hidden = false, $required = true) {

		$helper = $this->getHelper('question');

		$q = new Question($question, $default);

		if ($hidden) {
			$q->setHidden(true);
			$q->setHiddenFallback(false);
		}

		if ($required) {
			$q->setValidator([
				$this,
				'assertNotEmpty'
			]);
			$q->setMaxAttempts(2);
		}

		return $helper->ask($this->input, $this->output, $q);
	}

	/**
	 * Write messages to output buffer
	 *
	 * @param string|array $messages Messages
	 *
	 * @return void
	 */
	public function write($messages) {
		$this->output->writeln($messages);
	}

	/**
	 * Returns option value
	 *
	 * @param string $name Option name
	 *
	 * @return mixed
	 */
	public function option($name) {
		return $this->input->getOption($name);
	}

	/**
	 * Returns argument value
	 *
	 * @param string $name Argument name
	 *
	 * @return string
	 */
	public function argument($name) {
		return $this->input->getArgument($name);
	}

	/**
	 * Question validator for required user response
	 *
	 * @param mixed $answer User answer
	 *
	 * @return bool
	 */
	public function assertNotEmpty($answer) {
		if (empty($answer)) {
			throw new \RuntimeException('Please enter a required answer');
		}

		return $answer;
	}

	/**
	 * Dump and output system and error messages
	 * @return void
	 */
	protected function dumpRegisters() {
		$registers = _elgg_services()->systemMessages->loadRegisters();

		foreach ($registers as $prop => $values) {
			if (!empty($values)) {
				foreach ($values as $msg) {
					$tag = $prop == 'error' ? 'error' : 'info';
					$this->write(elgg_format_element($tag, [], $msg));
				}
			}
		}
	}
}