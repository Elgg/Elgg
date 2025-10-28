<?php

namespace Elgg\Traits;

/**
 * Adds methods for setting the current time (for testing)
 *
 * @internal
 */
trait TimeUsing {

	private ?\DateTimeImmutable $time = null;

	/**
	 * Get the time. If setCurrentTime() has not been set, this will return a new DateTimeImmutable().
	 *
	 * @param string $modifier Time modifier
	 *
	 * @return \DateTimeImmutable
	 * @see \DateTimeImmutable::modify
	 */
	public function getCurrentTime(string $modifier = ''): \DateTimeImmutable {
		$time = $this->time ?? new \DateTimeImmutable();
		if ($modifier) {
			$time = $time->modify($modifier);
		}
		
		return $time;
	}

	/**
	 * Set the current time.
	 *
	 * @param null|\DateTimeInterface $time Current time (empty for now)
	 *
	 * @return void
	 */
	public function setCurrentTime(?\DateTimeInterface $time = null): void {
		if ($time instanceof \DateTimeInterface) {
			$this->time = \DateTimeImmutable::createFromInterface($time);
		} else {
			$this->time = new \DateTimeImmutable();
		}
	}
	
	/**
	 * Reset the current time
	 * Use after the time has been set with setCurrentTime and it no longer needs to be locked
	 *
	 * @return void
	 * @since 4.3
	 */
	public function resetCurrentTime(): void {
		unset($this->time);
	}
}
