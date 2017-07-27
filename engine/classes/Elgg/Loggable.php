<?php
namespace Elgg;

/**
 * Enables adding a logger. Users should not assume $this->logger is set.
 *
 * @access private
 */
trait Loggable {

	/**
	 * @var Logger|null
	 */
	private $logger;

	/**
	 * Set (or remove) the logger
	 *
	 * @param Logger $logger Logger or null
	 * @return void
	 */
	public function setLogger(Logger $logger = null) {
		$this->logger = $logger;
	}
}
