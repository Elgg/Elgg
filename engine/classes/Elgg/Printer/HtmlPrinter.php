<?php

namespace Elgg\Printer;

use Elgg\Printer;

/**
 * Html Log Printer
 *
 * @access private
 */
class HtmlPrinter implements Printer {

	/**
	 * {@inheritdoc}
	 */
	public function write($data, $display, $level) {
		if ($display) {
			echo '<pre class="elgg-logger-data">';
			echo htmlspecialchars(print_r($data, true), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
			echo '</pre>';
		}

		error_log(print_r($data, true));
	}
}