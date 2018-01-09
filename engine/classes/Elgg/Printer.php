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
	 * @param mixed  $data  Data to print
	 * @param string $level Logging level
	 *
	 * @return mixed
	 */
	public function write($data, $level);
}
