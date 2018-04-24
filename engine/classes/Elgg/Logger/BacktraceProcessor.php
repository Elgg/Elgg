<?php

namespace Elgg\Logger;

use Elgg\Logger;

/**
 * Inject backtrace stack into the record
 */
class BacktraceProcessor {
	private $level;

	private $backtrace_level;

	/**
	 * Constructor
	 *
	 * @param int $level           Logging level
	 * @param int $backtrace_level Backtrance level (-1 for all)
	 */
	public function __construct($level = Logger::WARNING, $backtrace_level = -1) {
		$this->level = Logger::toMonologLevel($level);
		$this->backtrace_level = $backtrace_level;
	}

	/**
	 * Process recrod
	 *
	 * @param array $record Record
	 *
	 * @return array
	 */
	public function __invoke(array $record) {
		// return if the level is not high enough
		if ($record['level'] < $this->level) {
			return $record;
		}

		$backtrace_level = $this->backtrace_level;

		$stack = [];
		$backtrace = debug_backtrace();
		// never show this call.
		$backtrace = array_slice($backtrace,  9); // ignore the monolog stack

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

		$record['extra']['backtrace'] = $stack;

		return $record;
	}

}
