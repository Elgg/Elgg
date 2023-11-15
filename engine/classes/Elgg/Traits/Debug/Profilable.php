<?php

namespace Elgg\Traits\Debug;

use Elgg\Timer;

/**
 * Make an object accept a timer.
 *
 * @internal
 */
trait Profilable {

	private Timer $timer;

	/**
	 * Set a timer that should collect begin/end times for events
	 *
	 * @param Timer $timer Timer
	 * @return void
	 */
	public function setTimer(Timer $timer) {
		$this->timer = $timer;
	}
	
	/**
	 * Has a timer been set
	 *
	 * @return bool
	 */
	public function hasTimer(): bool {
		return isset($this->timer);
	}
	
	/**
	 * Start the timer (when enabled)
	 *
	 * @param array $keys Keys to identify period. E.g. ['startup', __FUNCTION__]
	 *
	 * @return void
	 * @see \Elgg\Timer::begin()
	 */
	protected function beginTimer(array $keys): void {
		if (!$this->hasTimer()) {
			return;
		}
		
		$this->timer->begin($keys);
	}
	
	/**
	 * Ends the timer (when enabled)
	 *
	 * @param array $keys Keys to identify period. E.g. ['startup', __FUNCTION__]
	 *
	 * @return void
	 * @see \Elgg\Timer::end()
	 */
	protected function endTimer(array $keys): void {
		if (!$this->hasTimer()) {
			return;
		}
		
		$this->timer->end($keys);
	}
}
