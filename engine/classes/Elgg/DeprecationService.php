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
 * @subpackage Deprecation
 * @since      1.11.0
 */
class DeprecationService {

	/**
	 * @var \ElggSession
	 */
	protected $session;

	/**
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Constructor
	 *
	 * @param \ElggSession $session Session service
	 * @param Logger       $logger  Logger service
	 */
	public function __construct(\ElggSession $session, Logger $logger) {
		$this->session = $session;
		$this->logger = $logger;
	}

	/**
	 * Sends a notice about deprecated use of a function, view, etc.
	 *
	 * @param string $msg             Message to log
	 * @param string $dep_version     Human-readable *release* version: 1.7, 1.8, ...
	 * @param int    $backtrace_level How many levels back to display the backtrace.
	 *                                Useful if calling from functions that are called
	 *                                from other places (like elgg_view()). Set to -1
	 *                                for a full backtrace.
	 * @return bool
	 */
	function sendNotice($msg, $dep_version, $backtrace_level = 1) {
		if (!$dep_version) {
			return false;
		}

		$elgg_version = elgg_get_version(true);
		$elgg_version_arr = explode('.', $elgg_version);
		$elgg_major_version = (int)$elgg_version_arr[0];
		$elgg_minor_version = (int)$elgg_version_arr[1];

		$dep_version_arr = explode('.', (string)$dep_version);
		$dep_major_version = (int)$dep_version_arr[0];
		$dep_minor_version = (int)$dep_version_arr[1];

		$msg = "Deprecated in $dep_major_version.$dep_minor_version: $msg Called from ";

		// Get a file and line number for the log. Skip over the function that
		// sent this notice and see who called the deprecated function itself.
		$stack = array();
		$backtrace = debug_backtrace();
		// never show this call.
		array_shift($backtrace);
		$i = count($backtrace);

		foreach ($backtrace as $trace) {
			$stack[] = "[#$i] {$trace['file']}:{$trace['line']}";
			$i--;

			if ($backtrace_level > 0) {
				if ($backtrace_level <= 1) {
					break;
				}
				$backtrace_level--;
			}
		}

		$msg .= implode("<br /> -> ", $stack);

		$this->logger->warn($msg);

		return true;
	}
}
