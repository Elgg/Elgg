<?php

namespace Elgg\Di;

use Elgg\Timer;

/**
 * Object accepting a timer
 */
interface Profitable {

	/**
	 * Set a timer that should collect begin/end times for events
	 *
	 * @param Timer $timer Timer
	 *
	 * @return void
	 */
	public function setTimer(Timer $timer);
}