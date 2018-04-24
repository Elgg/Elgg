<?php
namespace Elgg;

use Psr\Log\LoggerInterface;

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

	use Loggable;

	/**
	 * Constructor
	 *
	 * @param LoggerInterface $logger Logger service
	 */
	public function __construct(LoggerInterface $logger) {
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

		$msg = "Deprecated in $dep_version: $msg Called from ";

		// Get a file and line number for the log. Skip over the function that
		// sent this notice and see who called the deprecated function itself.
		$stack = [];
		$backtrace = debug_backtrace();
		// never show this call.
		array_shift($backtrace);
		$i = count($backtrace);

		foreach ($backtrace as $trace) {
			if (empty($trace['file'])) {
				// file/line not set for Closures
				$stack[] = "[#$i] unknown";
			} else {
				$stack[] = "[#$i] {$trace['file']}:{$trace['line']}";
			}

			$i--;

			if ($backtrace_level > 0) {
				if ($backtrace_level <= 1) {
					break;
				}
				$backtrace_level--;
			}
		}

		$msg .= implode(PHP_EOL . " -> ", $stack);

		$this->logger->warning($msg);

		return true;
	}
}
