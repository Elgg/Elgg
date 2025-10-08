<?php

namespace Elgg\Logger;

use Elgg\Logger;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * Inject backtrace stack into the record
 */
class BacktraceProcessor {
	
	protected Level $level;

	/**
	 * Constructor
	 *
	 * @param int $level           Logging level
	 * @param int $backtrace_level Backtrace level (-1 for all)
	 */
	public function __construct($level = Level::Warning, protected int $backtrace_level = -1) {
		$this->level = Logger::toMonologLevel($level);
	}

	/**
	 * Process record
	 *
	 * @param LogRecord $record Record
	 *
	 * @return LogRecord
	 */
	public function __invoke(LogRecord $record): LogRecord {
		// return if the level is not high enough
		if ($record->level->isLowerThan($this->level)) {
			return $record;
		}

		if (isset($record->context['throwable'])) {
			// rely on default output
			return $record;
		}

		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		foreach ($backtrace as $index => $trace) {
			if (isset($trace['file'])) {
				// strip the monolog stack
				if (str_contains($trace['file'], '\Monolog\\')) {
					unset($backtrace[$index]);
					continue;
				}

				if (str_contains($trace['file'], '\Elgg\Logger.php')) {
					unset($backtrace[$index]);
					continue;
				}

				if (str_contains($trace['file'], '\lib\elgglib.php')) {
					unset($backtrace[$index]);
					continue;
				}

				break;
			}
		}

		$i = count($backtrace);
		$backtrace_level = $this->backtrace_level;

		$stack = [];
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
