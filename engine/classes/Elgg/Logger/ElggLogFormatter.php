<?php

namespace Elgg\Logger;

use Monolog\Formatter\LineFormatter;

/**
 * Custom log formatter
 */
class ElggLogFormatter extends LineFormatter {

	/**
	 * {@inheritdoc}
	 */
	public function format(array $record) {

		$context = elgg_extract('context', $record, []);
		$exception = elgg_extract('exception', $context);

		if ($exception instanceof \Throwable) {
			$timestamp = isset($exception->timestamp) ? (int) $exception->timestamp : time();

			$dt = new \DateTime();
			$dt->setTimestamp($timestamp);
			$record['datetime'] = $dt;

			$eol = PHP_EOL;
			$message = "Exception at time {$timestamp}:{$eol}{$exception}{$eol}";
			$record['message'] = preg_replace('~\R~u', $eol, $message);

			if ($exception instanceof \DatabaseException) {
				$record['context']['sql'] = $exception->getQuery();
				$record['context']['params'] = $exception->getParameters();
			}
		}

		return parent::format($record);
	}

}