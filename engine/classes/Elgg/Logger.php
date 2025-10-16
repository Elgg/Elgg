<?php

namespace Elgg;

use Elgg\Cli\Application as CliApplication;
use Elgg\Cli\ErrorFormatter;
use Elgg\Cli\ErrorHandler;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Logger\BacktraceProcessor;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Level;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Logger
 *
 * Use elgg()->logger
 */
class Logger extends \Monolog\Logger {

	const CHANNEL = 'ELGG';

	const OFF = 600; // use highest log level for OFF

	/**
	 * Severity levels
	 * @var array
	 */
	protected static array $elgg_levels = [
		100 => LogLevel::DEBUG,
		200 => LogLevel::INFO,
		250 => LogLevel::NOTICE,
		300 => LogLevel::WARNING,
		400 => LogLevel::ERROR,
		500 => LogLevel::CRITICAL,
		550 => LogLevel::ALERT,
		600 => LogLevel::EMERGENCY,
	];

	protected string $level = LogLevel::EMERGENCY;

	protected array $disabled_stack = [];

	/**
	 * Build a new logger
	 *
	 * @param null|InputInterface  $input  Console input
	 * @param null|OutputInterface $output Console output
	 *
	 * @return static
	 */
	public static function factory(?InputInterface $input = null, ?OutputInterface $output = null) {
		$logger = new static(self::CHANNEL);

		if (\Elgg\Application::isCli()) {
			if (is_null($input) || is_null($output)) {
				$input = $input ?: \Elgg\Application::getStdIn();
				$output = $output ?: \Elgg\Application::getStdOut();

				$app = new CliApplication();
				$app->setup($input, $output);
			}

			$handler = new ErrorHandler(
				$output,
				\Elgg\Application::getStdErr(),
				true
			);

			$formatter = new ErrorFormatter();
			$formatter->allowInlineLineBreaks();
			$formatter->ignoreEmptyContextAndExtra();

			$handler->setFormatter($formatter);

			if ($output->isVeryVerbose()) {
				$handler->pushProcessor(new BacktraceProcessor(Level::Error));
			}
		} else {
			$handler = new ErrorLogHandler();

			$handler->pushProcessor(new WebProcessor());

			$formatter = new LineFormatter();
			$formatter->allowInlineLineBreaks();
			$formatter->ignoreEmptyContextAndExtra();

			$handler->setFormatter($formatter);

			$handler->pushProcessor(new MemoryUsageProcessor());
			$handler->pushProcessor(new MemoryPeakUsageProcessor());
			$handler->pushProcessor(new ProcessIdProcessor());
			$handler->pushProcessor(new BacktraceProcessor(Level::Warning));
		}

		$handler->pushProcessor(new PsrLogMessageProcessor());

		$logger->pushHandler($handler);

		// determine default log level
		$php_error_level = error_reporting();

		$level = LogLevel::CRITICAL;

		if (($php_error_level & E_NOTICE) == E_NOTICE) {
			$level = LogLevel::NOTICE;
		} else if (($php_error_level & E_WARNING) == E_WARNING) {
			$level = LogLevel::WARNING;
		} else if (($php_error_level & E_ERROR) == E_ERROR) {
			$level = LogLevel::ERROR;
		}

		$logger->setLevel($level);

		return $logger;
	}

	/**
	 * Assert the given level
	 *
	 * @param string $level the level to assert
	 *
	 * @return void
	 * @throws InvalidArgumentException
	 */
	protected function assertLevel(string $level): void {
		if (!in_array($level, self::$elgg_levels)) {
			throw new InvalidArgumentException("Using the log level '{$level}' is not allowed. Use one of the \Psr\Log\LogLevel constants.");
		}
	}

	/**
	 * Set the logging level
	 *
	 * @param string $level Level
	 *
	 * @return void
	 * @throws InvalidArgumentException
	 * @internal
	 */
	public function setLevel(string $level = LogLevel::EMERGENCY): void {
		$this->assertLevel($level);

		$this->level = $level;
	}

	/**
	 * Get the current logging level severity
	 *
	 * @param bool $severity If true, will return numeric representation of the logging level
	 *
	 * @return int|string
	 * @internal
	 */
	public function getLevel(bool $severity = true): int|string {
		return $severity ? array_search($this->level, self::$elgg_levels) : $this->level;
	}

	/**
	 * Check if a level is loggable under current logging level
	 *
	 * @param string $level Level name
	 *
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function isLoggable(string $level): bool {
		$this->assertLevel($level);

		$severity = (int) array_search($level, self::$elgg_levels);
		return $severity >= $this->getLevel();
	}

	/**
	 * {@inheritdoc}
	 * @throws InvalidArgumentException
	 */
	public function log($level, $message, array $context = []): void {
		$level = (string) $level;
		$this->assertLevel($level);

		if ($message instanceof \Throwable) {
			if (!isset($context['throwable']) && $this->isLoggable(LogLevel::NOTICE)) {
				$context['throwable'] = $message;
			}

			$message = $message->getMessage();
		}

		if (!empty($this->disabled_stack)) {
			// capture to top of stack
			end($this->disabled_stack);
			$key = key($this->disabled_stack);
			$this->disabled_stack[$key][] = [
				'message' => $message,
				'level' => $level,
			];
			
			return;
		}

		if (!$this->isLoggable($level)) {
			return;
		}

		parent::log($level, $message, $context);
	}

	/**
	 * {@inheritdoc}
	 */
	public function emergency($message, array $context = []): void {
		$this->log(LogLevel::EMERGENCY, $message, $context);
	}

	/**
	 * {@inheritdoc}
	 */
	public function alert($message, array $context = []): void {
		$this->log(LogLevel::ALERT, $message, $context);
	}

	/**
	 * {@inheritdoc}
	 */
	public function critical($message, array $context = []): void {
		$this->log(LogLevel::CRITICAL, $message, $context);
	}

	/**
	 * {@inheritdoc}
	 */
	public function error($message, array $context = []): void {
		$this->log(LogLevel::ERROR, $message, $context);
	}

	/**
	 * {@inheritdoc}
	 */
	public function warning($message, array $context = []): void {
		$this->log(LogLevel::WARNING, $message, $context);
	}

	/**
	 * {@inheritdoc}
	 */
	public function notice($message, array $context = []): void {
		$this->log(LogLevel::NOTICE, $message, $context);
	}

	/**
	 * {@inheritdoc}
	 */
	public function info($message, array $context = []): void {
		$this->log(LogLevel::INFO, $message, $context);
	}

	/**
	 * {@inheritdoc}
	 */
	public function debug($message, array $context = []): void {
		$this->log(LogLevel::DEBUG, $message, $context);
	}

	/**
	 * Temporarily disable logging and capture logs (before tests)
	 *
	 * Call disable() before your tests and enable() after. enable() will return a list of
	 * calls to log() (and helper methods) that were not acted upon.
	 *
	 * @note   This behaves like a stack. You must call enable() for each disable() call.
	 *
	 * @return void
	 * @see    enable()
	 * @internal
	 */
	public function disable() {
		$this->disabled_stack[] = [];
	}

	/**
	 * Restore logging and get record of log calls (after tests)
	 *
	 * @return array
	 * @see    disable()
	 * @internal
	 */
	public function enable() {
		return array_pop($this->disabled_stack);
	}
}
