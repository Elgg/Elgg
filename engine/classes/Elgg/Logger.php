<?php
namespace Elgg;

use Elgg\Printer\ErrorLogPrinter;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Logging
 * @since      1.9.0
 */
class Logger {

	const OFF = 0;
	const ERROR = 400;
	const WARNING = 300;
	const NOTICE = 250;
	const INFO = 200;

	protected static $levels = [
		0 => 'OFF',
		200 => 'INFO',
		250 => 'NOTICE',
		300 => 'WARNING',
		400 => 'ERROR',
	];

	/**
	 * @var int
	 */
	public static $verbosity;

	/**
	 * @var int The logging level
	 */
	protected $level = self::ERROR;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var array
	 */
	private $disabled_stack;

	/**
	 * @var Printer
	 */
	private $printer;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks   Hooks service
	 * @param Config             $config  Config
	 * @param Printer            $printer Printer
	 */
	public function __construct(PluginHooksService $hooks, Config $config, Printer $printer = null) {
		$this->hooks = $hooks;
		if (!isset($printer)) {
			$printer = new ErrorLogPrinter();
		}
		$this->printer = $printer;
		$this->config = $config;
		
		$php_error_level = error_reporting();

		// value is in settings.php, use until boot values are available
		if ($this->config->hasInitialValue('debug')) {
			$this->setLevel($this->config->debug);
			return;
		}

		$this->level = self::OFF;

		if (($php_error_level & E_NOTICE) == E_NOTICE) {
			$this->level = self::NOTICE;
		} elseif (($php_error_level & E_WARNING) == E_WARNING) {
			$this->level = self::WARNING;
		} elseif (($php_error_level & E_ERROR) == E_ERROR) {
			$this->level = self::ERROR;
		}
	}

	/**
	 * Set the logging level
	 *
	 * @param int $level The logging level
	 * @return void
	 */
	public function setLevel($level) {
		if (!$level) {
			// 0 or empty string
			$this->level = self::OFF;
			return;
		}

		// @todo Elgg has used string constants for logging levels
		if (is_string($level)) {
			$level = strtoupper($level);
			$level = array_search($level, self::$levels);

			if ($level !== false) {
				$this->level = $level;
			} else {
				$this->warn(__METHOD__ .": invalid level ignored.");
			}
			return;
		}

		if (isset(self::$levels[$level])) {
			$this->level = $level;
		} else {
			$this->warn(__METHOD__ .": invalid level ignored.");
		}
	}

	/**
	 * Get the current logging level
	 *
	 * @return int
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * Set custom printer
	 *
	 * @param Printer $printer Printer
	 * @return void
	 */
	public function setPrinter(Printer $printer) {
		$this->printer = $printer;
	}

	/**
	 * Add a message to the log
	 *
	 * @param string $message The message to log
	 * @param int    $level   The logging level
	 * @return bool Whether the messages was logged
	 */
	public function log($message, $level = self::NOTICE) {
		if ($this->disabled_stack) {
			// capture to top of stack
			end($this->disabled_stack);
			$key = key($this->disabled_stack);
			$this->disabled_stack[$key][] = [
				'message' => $message,
				'level' => $level,
			];
		}

		if ($this->level == self::OFF || $level < $this->level) {
			return false;
		}

		if (!array_key_exists($level, self::$levels)) {
			return false;
		}

		// when capturing, still use consistent return value
		if ($this->disabled_stack) {
			return true;
		}

		$levelString = self::$levels[$level];

		$this->process("$levelString: $message", $level);

		return true;
	}

	/**
	 * Log message at the ERROR level
	 *
	 * @param string $message The message to log
	 * @return bool
	 */
	public function error($message) {
		return $this->log($message, self::ERROR);
	}

	/**
	 * Log message at the WARNING level
	 *
	 * @param string $message The message to log
	 * @return bool
	 */
	public function warn($message) {
		return $this->log($message, self::WARNING);
	}

	/**
	 * Log message at the NOTICE level
	 *
	 * @param string $message The message to log
	 * @return bool
	 */
	public function notice($message) {
		return $this->log($message, self::NOTICE);
	}

	/**
	 * Log message at the INFO level
	 *
	 * @param string $message The message to log
	 * @return bool
	 */
	public function info($message) {
		return $this->log($message, self::INFO);
	}

	/**
	 * Dump data to log
	 *
	 * @param mixed $data The data to log
	 * @return void
	 */
	public function dump($data) {
		$this->process($data, self::ERROR);
	}

	/**
	 * Process logging data
	 *
	 * @param mixed $data  The data to process
	 * @param int   $level The logging level for this data
	 * @return void
	 */
	protected function process($data, $level) {
		
		// plugin can return false to stop the default logging method
		$params = [
			'level' => $level,
			'msg' => $data,
		];

		if (!$this->hooks->trigger('debug', 'log', $params, true)) {
			return;
		}

		$this->printer->write($data, $level);
	}

	/**
	 * Temporarily disable logging and capture logs (before tests)
	 *
	 * Call disable() before your tests and enable() after. enable() will return a list of
	 * calls to log() (and helper methods) that were not acted upon.
	 *
	 * @note This behaves like a stack. You must call enable() for each disable() call.
	 *
	 * @return void
	 * @see enable()
	 * @access private
	 * @internal
	 */
	public function disable() {
		$this->disabled_stack[] = [];
	}

	/**
	 * Restore logging and get record of log calls (after tests)
	 *
	 * @return array
	 * @see disable()
	 * @access private
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
	 * @access private
	 * @internal
	 */
	public function setHooks(PluginHooksService $hooks) {
		$this->hooks = $hooks;
	}
}
