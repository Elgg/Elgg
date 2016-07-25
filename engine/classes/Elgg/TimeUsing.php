<?php
namespace Elgg;

use DateTime;

/**
 * Adds methods for setting the current time (for testing)
 *
 * @access private
 */
trait TimeUsing {

	/**
	 * @var DateTime
	 */
	private $time;

	/**
	 * Get the (cloned) time. If setCurrentTime() has not been set, this will return a new DateTime().
	 *
	 * @see DateTime::modify
	 *
	 * @param string $modifier Time modifier
	 * @return DateTime
	 */
	public function getCurrentTime($modifier = '') {
		$time = $this->time ? $this->time : new DateTime();
		$time = clone $time;
		if ($modifier) {
			$time->modify($modifier);
		}
		return $time;
	}

	/**
	 * Set the current time.
	 *
	 * @param DateTime $time Current time (empty for now)
	 * @return void
	 */
	public function setCurrentTime(DateTime $time = null) {
		if (!$time) {
			$time = new DateTime();
		}
		$this->time = clone $time;
	}
}
