<?php

namespace Elgg\Logger;

use Elgg\Exceptions\DatabaseException;
use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;

/**
 * Custom log formatter
 */
class ElggLogFormatter extends LineFormatter {

	/**
	 * {@inheritdoc}
	 */
	public function format(LogRecord $record): string {
		$context = $record->context;
		$exception = elgg_extract('exception', $context);

		if (!$exception instanceof \Throwable) {
			return parent::format($record);
		}
		
		$dt = new \DateTimeImmutable();
		
		$eol = PHP_EOL;
		$message = "Exception at time {$dt->getTimestamp()}:{$eol}{$exception->getMessage()}{$eol}";
		$record_message = preg_replace('~\R~u', $eol, $message);

		if ($exception instanceof DatabaseException) {
			$context['sql'] = $exception->getQuery();
			$context['params'] = $exception->getParameters();
		}
		
		unset($context['exception']);

		return parent::format(new LogRecord($dt, $record->channel, $record->level, $record_message, $context, $record->extra, $record->formatted));
	}
}
