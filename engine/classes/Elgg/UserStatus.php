<?php

/**
 * This object represents the logged in/out state of a user.
 *
 * Why is this needed? Some functions need to distinguish between 1. knowing a user is not logged
 * in and 2. not knowing the status of a user (e.g. passing this object as null)
 *
 * Consider a function that type-hints ElggUser with default null. If null is passed in (or the
 * argument is not supplied), the function cannot tell if null means "not logged in" or
 * "status unknown". If this is important, the author can type-hint on this class instead.
 *
 * @access private
 */
class Elgg_UserStatus {

	protected $user;

	/**
	 * @param ElggUser|null $logged_in_user
	 */
	public function __construct(ElggUser $logged_in_user = null) {
		$this->user = $logged_in_user;
	}

	/**
	 * @return bool
	 */
	public function isLoggedIn() {
		return (bool) $this->user;
	}

	/**
	 * @return ElggUser|null
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return Elgg_UserStatus
	 */
	public static function fromSession() {
		return new self(elgg_get_logged_in_user_entity());
	}
}
