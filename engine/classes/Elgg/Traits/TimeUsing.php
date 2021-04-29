<?php

namespace Elgg\Traits;

/**
 * Adds methods for setting the current time (for testing)
 *
 * @internal
 */
trait TimeUsing {

	/**
	 * @var \DateTime
	 */
	private $time;

	/**
	 * Get the (cloned) time. If setCurrentTime() has not been set, this will return a new DateTime().
	 *
	 * @param string $modifier Time modifier
	 *
	 * @return \DateTime
	 * @see \DateTime::modify
	 */
	public function getCurrentTime($modifier = '') {
		$time = $this->time ? $this->time : new \DateTime();
		$time = clone $time;
		if ($modifier) {
			$time->modify($modifier);
		}
		return $time;
	}

	/**
	 * Set the current time.
	 *
	 * @param \DateTime $time Current time (empty for now)
	 *
	 * @return void
	 */
	public function setCurrentTime(\DateTime $time = null) {
		if (!$time) {
			$time = new \DateTime();
		}
		$this->time = clone $time;
	}
}
