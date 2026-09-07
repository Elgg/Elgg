<?php

namespace Elgg\Traits\Seeding;

/**
 * Group helpers for seeding
 *
 * @since 4.0
 * @internal
 */
trait GroupHelpers {
	
	private array $visibility = [
		ACCESS_PUBLIC,
		ACCESS_LOGGED_IN,
		ACCESS_PRIVATE,
	];
	
	private array $content_access_modes = [
		\ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
		\ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
	];
	
	private array $membership = [
		\ElggGroup::MEMBERSHIP_PUBLIC,
		\ElggGroup::MEMBERSHIP_CLOSED,
		\ElggGroup::MEMBERSHIP_INVITE_ONLY,
	];
	
	/**
	 * Returns random visibility value
	 * @return int
	 */
	public function getRandomGroupVisibility(): int {
		$key = array_rand($this->visibility, 1);
		
		return $this->visibility[$key];
	}
	
	/**
	 * Returns random content access mode value
	 * @return string
	 */
	public function getRandomGroupContentAccessMode(): string {
		$key = array_rand($this->content_access_modes, 1);
		
		return $this->content_access_modes[$key];
	}
	
	/**
	 * Returns random membership mode
	 * @return int
	 */
	public function getRandomGroupMembership(): int {
		$key = array_rand($this->membership, 1);
		
		return $this->membership[$key];
	}
}
