<?php

namespace Elgg\Cli;

use Elgg\Logger\ElggLogFormatter;
use Monolog\Level;
use Monolog\LogRecord;
use Symfony\Component\Console\Helper\FormatterHelper;

/**
 * Format errors for console output
 */
class ErrorFormatter extends ElggLogFormatter {

	const SIMPLE_FORMAT = '%level_name%: %message%';

	/**
	 * {@inheritdoc}
	 */
	public function format(LogRecord $record): string {
		$message = parent::format($record);

		$formatter = new FormatterHelper();

		switch ($record->level) {
			case Level::Emergency:
			case Level::Critical:
			case Level::Alert:
			case Level::Error:
				$style = 'error';
				break;

			case Level::Warning:
				$style = 'comment';
				break;

			default:
				$style = 'info';
				break;
		}

		return $formatter->formatBlock($message, $style);
	}
}
