<?php

namespace Elgg\Traits\Seeding;

/**
 * Group helpers for seeding
 *
 * @since 4.0
 * @internal
 */
trait GroupHelpers {
	
	private $visibility = [
		ACCESS_PUBLIC,
		ACCESS_LOGGED_IN,
		ACCESS_PRIVATE,
	];
	
	private $content_access_modes = [
		\ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
		\ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
	];
	
	private $membership = [
		ACCESS_PUBLIC,
		ACCESS_PRIVATE,
	];
	
	/**
	 * Returns random visibility value
	 * @return int
	 */
	public function getRandomGroupVisibility() {
		$key = array_rand($this->visibility, 1);
		
		return $this->visibility[$key];
	}
	
	/**
	 * Returns random content access mode value
	 * @return string
	 */
	public function getRandomGroupContentAccessMode() {
		$key = array_rand($this->content_access_modes, 1);
		
		return $this->content_access_modes[$key];
	}
	
	/**
	 * Returns random membership mode
	 * @return mixed
	 */
	public function getRandomGroupMembership() {
		$key = array_rand($this->membership, 1);
		
		return $this->membership[$key];
	}
}
