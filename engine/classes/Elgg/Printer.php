<?php

namespace Elgg;

/**
 * Log printer interface
 *
 * @access private
 */
interface Printer {

	/**
	 * Prints data
	 *
	 * @param mixed  $data    Data to print
	 * @param bool   $display If display is expected by the logger
	 * @param string $level   Logging level
	 *
	 * @return mixed
	 */
	public function write($data, $display, $level);
}
