<?php
namespace Elgg;

/**
 * Make an object accept a timer.
 *
 * @access private
 */
trait Profilable {

	/**
	 * @var Timer|null
	 */
	private $timer;

	/**
	 * Set a timer that should collect begin/end times for events
	 *
	 * @param Timer $timer Timer
	 * @return void
	 */
	public function setTimer(Timer $timer) {
		$this->timer = $timer;
	}
}
