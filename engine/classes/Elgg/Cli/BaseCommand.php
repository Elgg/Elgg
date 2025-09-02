<?php

namespace Elgg\Cli;

use Elgg\Exceptions\RuntimeException;
use Elgg\Traits\Loggable;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Adds interaction to a console command
 */
abstract class BaseCommand extends \Symfony\Component\Console\Command\Command {

	use Loggable;

	const DEFAULT_VERBOSITY = OutputInterface::VERBOSITY_NORMAL;

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
		/* @var $helper QuestionHelper */
		$helper = $this->getHelper('question');

		$question = trim($question);
		$question = rtrim($question, ':');
		if (is_scalar($default) && !is_bool($default)) {
			$question .= " [{$default}]";
		}
		
		$question .= ': ';
		
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
	 * Dump a variable
	 *
	 * @param mixed $data Data to dump
	 *
	 * @return void
	 * @deprecated 6.3
	 */
	final public function dump($data) {
		elgg_deprecated_notice(__METHOD__ . ' is deprecated and will be removed', '6.3');
		
		VarDumper::dump($data);
	}

	/**
	 * Write messages to output buffer
	 *
	 * @param string|array $messages Data or messages
	 * @param string       $style    Output style
	 *
	 * @return void
	 */
	final public function write($messages, $style = 'info'): void {
		$formatter = new FormatterHelper();

		if (!$this->output->getFormatter()->hasStyle($style)) {
			elgg_deprecated_notice(__METHOD__ . " using '{$style}' needs to be a supported style, use [info, error, comment or question]", '6.3');
			
			switch ($style) {
				case LogLevel::EMERGENCY:
				case LogLevel::CRITICAL:
				case LogLevel::ALERT:
				case LogLevel::ERROR:
					$style = 'error';
					break;
				
				case LogLevel::WARNING:
					$style = 'comment';
					break;
				
				default:
					$style = 'info';
					break;
			}
		}

		$message = $formatter->formatBlock($messages, $style);
		$this->output->writeln($message);
	}

	/**
	 * Print an error
	 *
	 * @param string $message Error message
	 *
	 * @return void
	 * @deprecated 6.3 use elgg_log($message, LogLevel::ERROR)
	 */
	public function error($message) {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated, use elgg_log()', '6.3');
		
		elgg_log($message, LogLevel::ERROR);
	}

	/**
	 * Print a notice
	 *
	 * @param string $message Error message
	 *
	 * @return void
	 * @deprecated 6.3 use elgg_log($message, LogLevel::NOTICE)
	 */
	public function notice($message) {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated, use elgg_log()', '6.3');
		
		elgg_log($message, LogLevel::NOTICE);
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
	 * @return mixed
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
	 * @throws RuntimeException
	 */
	public function assertNotEmpty($answer) {
		if (empty($answer)) {
			throw new RuntimeException('Please enter a required answer');
		}

		return $answer;
	}

	/**
	 * Dump and output system and error messages
	 * @return void
	 */
	final protected function dumpRegisters() {
		$registers = _elgg_services()->system_messages->loadRegisters();

		foreach ($registers as $prop => $values) {
			if (!empty($values)) {
				foreach ($values as $msg) {
					$prop === 'error' ? $this->write($msg, 'error') : $this->write($msg);
				}
			}
		}
	}
}
