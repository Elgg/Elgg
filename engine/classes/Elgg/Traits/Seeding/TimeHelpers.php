<?php

namespace Elgg\Traits\Seeding;

use Elgg\Values;

/**
 * Trait to add time helpers
 *
 * @since 4.0
 * @internal
 */
trait TimeHelpers {
	
	/**
	 * @var int
	 */
	protected $create_since;
	
	/**
	 * @var int
	 */
	protected $create_until;
	
	/**
	 * Set a time for entities to be created after
	 *
	 * @param mixed $since Time value for first creation date
	 *
	 * @return void
	 * @see \Elgg\Values::normalizeTimestamp()
	 */
	public function setCreateSince($since = 'now') {
		$this->create_since = Values::normalizeTimestamp($since);
	}
	
	/**
	 * Set a time for entities to be created until
	 *
	 * @param mixed $until Time value for last creation date
	 *
	 * @return void
	 * @see \Elgg\Values::normalizeTimestamp()
	 */
	public function setCreateUntil($until = 'now') {
		$this->create_until = Values::normalizeTimestamp($until);
	}
	
	/**
	 * Get a random timestamp between a lower and upper time
	 *
	 * @return int
	 */
	public function getRandomCreationTimestamp() : int {
		$since = $this->create_since ?: time();
		$until = $this->create_until ?: time();
		
		return $this->faker()->numberBetween($since, $until);
	}
}
