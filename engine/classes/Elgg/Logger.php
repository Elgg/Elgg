<?php
namespace Elgg;

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

	protected static $levels = array(
		0 => 'OFF',
		200 => 'INFO',
		250 => 'NOTICE',
		300 => 'WARNING',
		400 => 'ERROR',
	);

	/** @var int $level The logging level */
	protected $level = self::ERROR;

	/** @var bool $display Display to user? */
	protected $display = false;

	/** @var \Elgg\PluginHooksService $hooks */
	protected $hooks;

	/** @var \stdClass Global Elgg configuration */
	private $CONFIG;

	/**
	 * Constructor
	 *
	 * @param \Elgg\PluginHooksService $hooks Hooks service
	 */
	public function __construct(\Elgg\PluginHooksService $hooks) {
		global $CONFIG;
		
		$this->CONFIG = $CONFIG;
		$this->hooks = $hooks;
	}

	/**
	 * Set the logging level
	 *
	 * @param int $level The logging level
	 * @return void
	 */
	public function setLevel($level) {
		// @todo Elgg has used string constants for logging levels
		if (is_string($level)) {
			$levelStringsToInts = array_flip(self::$levels);
			$level = $levelStringsToInts[$level];
		}
		$this->level = $level;
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
	 * Set whether the logging should be displayed to the user
	 *
	 * Whether data is actually displayed to the user depends on this setting
	 * and other factors such as whether we are generating a JavaScript or CSS
	 * file.
	 *
	 * @param bool $display Whether to display logging
	 * @return void
	 */
	public function setDisplay($display) {
		$this->display = $display;
	}

	/**
	 * Add a message to the log
	 *
	 * @param string $message The message to log
	 * @param int    $level   The logging level
	 * @return bool Whether the messages was logged
	 */
	public function log($message, $level = self::NOTICE) {
		if ($this->level == self::OFF || $level < $this->level) {
			return false;
		}

		if (!array_key_exists($level, self::$levels)) {
			return false;
		}

		$levelString = self::$levels[$level];

		// notices and below never displayed to user
		$display = $this->display && $level > self::NOTICE;

		$this->process("$levelString: $message", $display, $level);

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
	 * Dump data to log or screen
	 *
	 * @param mixed $data    The data to log
	 * @param bool  $display Whether to include this in the HTML page
	 * @return void
	 */
	public function dump($data, $display = true) {
		$this->process($data, $display, self::ERROR);
	}

	/**
	 * Process logging data
	 *
	 * @param mixed $data    The data to process
	 * @param bool  $display Whether to display the data to the user. Otherwise log it.
	 * @param int   $level   The logging level for this data
	 * @return void
	 */
	protected function process($data, $display, $level) {
		

		// plugin can return false to stop the default logging method
		$params = array(
			'level' => $level,
			'msg' => $data,
			'display' => $display,
			'to_screen' => $display,
		);

		if (!$this->hooks->trigger('debug', 'log', $params, true)) {
			return;
		}

		// Do not want to write to screen before page creation has started.
		// This is not fool-proof but probably fixes 95% of the cases when logging
		// results in data sent to the browser before the page is begun.
		if (!isset($this->CONFIG->pagesetupdone)) {
			$display = false;
		}

		// Do not want to write to JS or CSS pages
		if (elgg_in_context('js') || elgg_in_context('css')) {
			$display = false;
		}

		if ($display == true) {
			echo '<pre class="elgg-logger-data">';
			echo htmlspecialchars(print_r($data, true), ENT_QUOTES, 'UTF-8');
			echo '</pre>';
		} else {
			error_log(print_r($data, true));
		}
	}
}

