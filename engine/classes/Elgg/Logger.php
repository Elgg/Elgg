<?php

namespace Elgg;

use Elgg\Cli\Application as CliApplication;
use Elgg\Cli\ErrorFormatter;
use Elgg\Cli\ErrorHandler;
use Elgg\Logger\BacktraceProcessor;
use Elgg\Logger\ElggLogFormatter;
use Monolog\Handler\ErrorLogHandler;
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
	protected static $elgg_levels = [
		0 => false,
		100 => LogLevel::DEBUG,
		200 => LogLevel::INFO,
		250 => LogLevel::NOTICE,
		300 => LogLevel::WARNING,
		400 => LogLevel::ERROR,
		500 => LogLevel::CRITICAL,
		550 => LogLevel::ALERT,
		600 => LogLevel::EMERGENCY,
	];

	/**
	 * A map of legacy string levels
	 * @var array
	 */
	protected static $legacy_levels = [
		'OFF' => false,
		'INFO' => LogLevel::INFO,
		'NOTICE' => LogLevel::NOTICE,
		'WARNING' => LogLevel::WARNING,
		'ERROR' => LogLevel::ERROR,
	];

	/**
	 * @var false|string The logging level
	 */
	protected $level;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var array
	 */
	private $disabled_stack;

	/**
	 * Build a new logger
	 *
	 * @param $input  InputInterface  Console input
	 * @param $output OutputInterface Console output
	 *
	 * @return static
	 */
	public static function factory(InputInterface $input = null, OutputInterface $output = null) {
		$logger = new static(self::CHANNEL);

		if (\Elgg\Application::isCli()) {
			if (is_null($input) || is_null($output)) {
				$input = $input ? : \Elgg\Application::getStdIn();
				$output = $output ? : \Elgg\Application::getStdOut();

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

			$handler->pushProcessor(new BacktraceProcessor(self::ERROR));
		} else {
			$handler = new ErrorLogHandler();

			$handler->pushProcessor(new WebProcessor());

			$formatter = new ElggLogFormatter();
			$formatter->allowInlineLineBreaks();
			$formatter->ignoreEmptyContextAndExtra();

			$handler->setFormatter($formatter);

			$handler->pushProcessor(new MemoryUsageProcessor());
			$handler->pushProcessor(new MemoryPeakUsageProcessor());
			$handler->pushProcessor(new ProcessIdProcessor());
			$handler->pushProcessor(new BacktraceProcessor(self::WARNING));
		}

		$handler->pushProcessor(new PsrLogMessageProcessor());

		$logger->pushHandler($handler);

		$logger->setLevel();

		return $logger;
	}

	/**
	 * Normalizes legacy string or numeric representation of the level to LogLevel strings
	 *
	 * @param mixed $level Level
	 *
	 * @return string|false
	 */
	protected function normalizeLevel($level = null) {
		if (!$level) {
			return false;
		}

		if (array_key_exists($level, self::$legacy_levels)) {
			$level = self::$legacy_levels[$level];
			if ($level === false) {
				// can't array_key_exists for false
				return 0;
			}
		}

		if (array_key_exists($level, self::$elgg_levels)) {
			$level = self::$elgg_levels[$level];
		}

		if (!in_array($level, self::$elgg_levels)) {
			$level = false;
		}

		return $level;
	}

	/**
	 * Set the logging level
	 *
	 * @param mixed $level Level
	 *
	 * @return void
	 * @internal
	 */
	public function setLevel($level = null) {
		if (!isset($level)) {
			$php_error_level = error_reporting();

			$level = false;

			if (($php_error_level & E_NOTICE) == E_NOTICE) {
				$level = LogLevel::NOTICE;
			} else if (($php_error_level & E_WARNING) == E_WARNING) {
				$level = LogLevel::WARNING;
			} else if (($php_error_level & E_ERROR) == E_ERROR) {
				$level = LogLevel::ERROR;
			}
		}

		$this->level = $this->normalizeLevel($level);
	}

	/**
	 * Get the current logging level severity
	 *
	 * @param bool $severity If true, will return numeric representation of the logging level
	 *
	 * @return int|string|false
	 * @internal
	 */
	public function getLevel($severity = true) {
		if ($severity) {
			return array_search($this->level, self::$elgg_levels);
		}

		return $this->level;
	}

	/**
	 * Check if a level is loggable under current logging level
	 *
	 * @param mixed $level Level name or severity code
	 *
	 * @return bool
	 */
	public function isLoggable($level) {
		$level = $this->normalizeLevel($level);

		$severity = array_search($level, self::$elgg_levels);
		if (!$this->getLevel() || $severity < $this->getLevel()) {
			return false;
		}

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function log($level, $message, array $context = []): void {

		$level = $this->normalizeLevel($level);

		if (!empty($this->disabled_stack)) {
			// capture to top of stack
			end($this->disabled_stack);
			$key = key($this->disabled_stack);
			$this->disabled_stack[$key][] = [
				'message' => $message,
				'level' => $level,
			];
		}

		if (!$this->isLoggable($level)) {
			return;
		}

		// when capturing, still use consistent return value
		if (!empty($this->disabled_stack)) {
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
	 * Dump data to log
	 *
	 * @param mixed $data The data to log
	 *
	 * @return void
	 */
	public function dump($data) {
		$this->log(LogLevel::ERROR, $data);
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

	/**
	 * Reset the hooks service for this instance (testing)
	 *
	 * @param PluginHooksService $hooks the plugin hooks service
	 *
	 * @return void
	 * @internal
	 */
	public function setHooks(PluginHooksService $hooks) {
		$this->hooks = $hooks;
	}
}
