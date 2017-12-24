<?php

namespace Elgg\Printer;

use Elgg\Printer;

/**
 * Default Log Printer
 *
 * @access private
 */
class ErrorLogPrinter implements Printer {

	/**
	 * {@inheritdoc}
	 */
	public function write($data, $level) {
		error_log(print_r($data, true));
	}
}
