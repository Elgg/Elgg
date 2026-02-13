<?php

namespace Elgg\Developers;

use Elgg\Exceptions\DatabaseException;
use Monolog\Formatter\LineFormatter;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * Console log formatter
 */
class ConsoleLogFormatter extends LineFormatter {

	/**
	 * {@inheritdoc}
	 */
	public function format(LogRecord $record): string {

		$context = $record->context;

		$message_parts = ['%c' . $record->channel];
		$message_parts[] = '%c' . strtolower($record->level->getName()) . '%c';

		$record_message = $record->message;

		$exception = elgg_extract('exception', $context);
		if ($exception instanceof \Throwable) {
			unset($context['exception']);
			unset($context['throwable']);

			$record_message = $exception->getMessage();

			$context['backtrace'] = $exception->getTraceAsString();

			if ($exception instanceof DatabaseException) {
				$context['sql'] = $exception->getQuery();
				$context['params'] = $exception->getParameters();
			}
		}

		$message_parts[] = $record->datetime->format($this->dateFormat);
		$message_parts[] = addslashes(preg_replace('/[\s]+/', ' ', $record_message));

		$message = implode(' - ', $message_parts);

		$extra_details = '';
		foreach ($context as $key => $value) {
			$key = addslashes($key);

			$extra_details .= "console.group('{$key}');";
			$extra_details .= 'console.table(' . json_encode($value) . ');';
			$extra_details .= 'console.groupEnd();';
		}

		foreach ($record->extra as $key => $value) {
			$key = addslashes($key);

			$extra_details .= "console.group('{$key}');";
			$extra_details .= 'console.table(' . json_encode($value) . ');';
			$extra_details .= 'console.groupEnd();';
		}

		$color = $this->getConsoleColorForLevel($record->level);
		$styling_args = [
			'font-weight: normal;',
			"color: {$color}; font-weight: normal;",
			'font-weight: normal;'
		];

		if (empty($extra_details)) {
			$log_function = $this->getConsoleMethodForLevel($record->level);
			return "console.{$log_function}('{$message}', '" . implode("', '", $styling_args) . "');";
		} else {
			$result = "console.groupCollapsed('{$message}', '" . implode("', '", $styling_args) . "');";

			$result .= $extra_details;

			$result .= 'console.groupEnd();';

			return $result;
		}
	}

	/**
	 * Returns the console method to use for outputting a certain loglevel
	 *
	 * @param Level $level the level to check
	 *
	 * @return string
	 */
	protected function getConsoleMethodForLevel(Level $level): string {
		return match ($level) {
			Level::Debug => 'debug',
			Level::Info, Level::Notice => 'info',
			Level::Warning => 'warn',
			Level::Error, Level::Critical, Level::Alert, Level::Emergency => 'error',
			default => 'log',
		};
	}

	/**
	 * Returns a corresponding color for a given log level to be used in styling of the console log
	 * 	 *
	 * @param Level $level the level to check
	 *
	 * @return string
	 */
	protected function getConsoleColorForLevel(Level $level): string {
		return match ($level) {
			Level::Warning => 'yellow',
			Level::Error, Level::Critical, Level::Alert, Level::Emergency => 'red',
			default => 'white',
		};
	}
}
