<?php

namespace Elgg\DevelopersPlugin;

use Monolog\Formatter\HtmlFormatter;
use Monolog\Logger;

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
	protected function addRow($th, $td = ' ', $escapeTd = true) {
		$th = htmlspecialchars($th, ENT_NOQUOTES, 'UTF-8');
		if ($escapeTd) {
			$td = '<pre>' . htmlspecialchars($td, ENT_NOQUOTES, 'UTF-8') . '</pre>';
		}

		return "<tr class=\"developers-error-log-row\"><th>$th:</th><td>$td</td></tr>";
	}

	/**
	 * Formats a log record.
	 *
	 * @param  array $record A record to format
	 *
	 * @return mixed The formatted record
	 */
	public function format(array $record) {
		
		if (elgg_get_viewtype() !== 'default') {
			// prevent 'view not found' deadloops in other viewtypes (eg failsafe)
			return parent::format($record);
		}
		
		$context = elgg_extract('context', $record, []);
		$exception = elgg_extract('exception', $context);
		
		$level = strtolower(\Elgg\Logger::getLevelName($record['level']));
		
		$message_vars = [];
		$message_vars['title'] = $level; // help prevent elgg_echo() missing language key recursion
		
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

			$message_vars['title'] = "EXCEPTION $timestamp";
		}
		
		$output = '<table class="elgg-table elgg-table-alt">';

		$output .= $this->addRow('Message', (string) $record['message']);
		$output .= $this->addRow('Time', $record['datetime']->format($this->dateFormat));
		$output .= $this->addRow('Channel', $record['channel']);

		if ($record['context']) {
			foreach ($record['context'] as $key => $value) {
				$output .= $this->addRow($key, $this->convertToString($value));
			}
		}
		if ($record['extra']) {
			foreach ($record['extra'] as $key => $value) {
				$output .= $this->addRow($key, $this->convertToString($value));
			}
		}
		
		$output .= '</table>';
		
		return elgg_view_message($level, $output, $message_vars);
	}

}
