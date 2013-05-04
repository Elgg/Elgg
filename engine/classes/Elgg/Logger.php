<?php

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
class Elgg_Logger {

	const OFF = 0;
	const ERROR = 400;
	const WARNING = 300;
	const NOTICE = 250;

	protected static $levels = array(
		0 => 'OFF',
		250 => 'NOTICE',
		300 => 'WARNING',
		400 => 'ERROR',
	);

	/**
	 * @var int $level The trace level
	 */
	private $level = self::OFF;

	/** @var ElggPluginHookService */
	private $hooks;

	/**
	 * Constructor
	 *
	 * @param Elgg_PluginHookService $hooks Hooks service
	 */
	public function __construct(Elgg_PluginHookService $hooks) {
		$this->hooks = $hooks;
	}


	/**
	 * Add a message to the log
	 *
	 * @param string $message The message to log
	 * @param int    $level   The logging level
	 * @return bool Whether the messages was logged
	 */
	public function log($message, $level = self::NOTICE) {

		if ($this->level != self::OFF) {

			// do not send logging at notice level or below to screen
			$to_screen = !($this->level <= self::NOTICE);

			$levelString = self::$levels[$level];

			switch ($level) {
				case self::ERROR:
					// always log errors
					$this->dump("$levelString: $message", $to_screen, $level);
					break;
				case self::WARNING:
					if ($this->level <= self::WARNING) {
						$this->dump("$levelString: $message", $to_screen, $level);
					}
					break;
				case self::NOTICE:
					if ($this->level <= self::NOTICE) {
						$this->dump("$levelString: $message", FALSE, $level);
					}
					break;
				default:
					return false;
					break;
			}

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Dump data to log or screen
	 *
	 * @param mixed $value     The data to log
	 * @param bool  $to_screen Whether to include this in the HTML page
	 * @param int   $level     The logging level
	 * @return bool Whether the messages was logged
	 */
	public function dump($value, $to_screen = TRUE, $level = self::NOTICE) {
		global $CONFIG;

		// plugin can return false to stop the default logging method
		$params = array(
			'level' => $level,
			'msg' => $value,
			'to_screen' => $to_screen,
		);

		if (!$this->hooks->trigger('debug', 'log', $params, true)) {
			return;
		}

		// Do not want to write to screen before page creation has started.
		// This is not fool-proof but probably fixes 95% of the cases when logging
		// results in data sent to the browser before the page is begun.
		if (!isset($CONFIG->pagesetupdone)) {
			$to_screen = FALSE;
		}

		// Do not want to write to JS or CSS pages
		if (elgg_in_context('js') || elgg_in_context('css')) {
			$to_screen = FALSE;
		}

		if ($to_screen == TRUE) {
			echo '<pre>';
			print_r($value);
			echo '</pre>';
		} else {
			error_log(print_r($value, TRUE));
		}
	}

	/**
	 * Set the trace level of the logger
	 *
	 * @param int $level The trace level
	 */
	public function setLevel($level) {
		$this->level = $level;
	}
}
