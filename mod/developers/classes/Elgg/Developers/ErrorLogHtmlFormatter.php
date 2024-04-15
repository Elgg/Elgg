<?php

namespace Elgg\Developers;

use Elgg\Exceptions\DatabaseException;
use Monolog\Formatter\HtmlFormatter;
use Monolog\LogRecord;

/**
 * HTML error log formatter
 */
class ErrorLogHtmlFormatter extends HtmlFormatter {

	/**
	 * Creates an HTML table row
	 *
	 * @param  string $th       Row header content
	 * @param  string $td       Row standard cell content
	 * @param  bool   $escapeTd false if td content must not be html escaped
	 *
	 * @return string
	 */
	protected function addRow(string $th, string $td = ' ', bool $escapeTd = true): string {
		$th = htmlspecialchars($th, ENT_NOQUOTES, 'UTF-8');
		if ($escapeTd) {
			$td = elgg_format_element('pre', [], htmlspecialchars($td, ENT_NOQUOTES, 'UTF-8'));
		}

		return "<tr><th>{$th}:</th><td>{$td}</td></tr>";
	}

	/**
	 * Formats a log record.
	 *
	 * @param  array $record A record to format
	 *
	 * @return mixed The formatted record
	 */
	public function format(LogRecord $record): string {
		
		if (elgg_get_viewtype() !== 'default') {
			// prevent 'view not found' deadloops in other viewtypes (eg failsafe)
			return parent::format($record);
		}
		
		$context = $record->context;
		$exception = elgg_extract('exception', $context);
		
		$level = strtolower($record->level->getName());
		
		$message_vars = [];
		$message_vars['title'] = $level; // help prevent elgg_echo() missing language key recursion
		
		$datetime = $record->datetime;
		$record_message = $record->message;
		if ($exception instanceof \Throwable) {
			$datetime = new \DateTime();
			
			$eol = PHP_EOL;
			$timestamp = $datetime->getTimestamp();
			$message = "Exception at time {$timestamp}:{$eol}{$exception}{$eol}";
			$record_message = preg_replace('~\R~u', $eol, $message);

			if ($exception instanceof DatabaseException) {
				$context['sql'] = $exception->getQuery();
				$context['params'] = $exception->getParameters();
			}

			$message_vars['title'] = "EXCEPTION {$timestamp}";
		}
		
		$message_vars['title'] .= ' - ' . $datetime->format($this->dateFormat);
		$message_vars['menu'] = $record->channel;
		
		$rows = $this->addRow('Message', (string) $record_message);

		foreach ($context as $key => $value) {
			$rows .= $this->addRow($key, $this->convertToString($value));
		}
		
		foreach ($record->extra as $key => $value) {
			$rows .= $this->addRow($key, $this->convertToString($value));
		}
		
		$body = elgg_format_element('table', ['class' => ['elgg-table', 'elgg-table-alt']], $rows);
		return elgg_view_message($level, $body, $message_vars);
	}
}
