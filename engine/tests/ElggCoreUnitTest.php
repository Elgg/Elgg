<?php

/**
 * Elgg Core Unit Tester
 *
 * This class is to be extended by all Elgg unit tests. As such, any method here
 * will be available to the tests.
 */
abstract class ElggCoreUnitTest extends UnitTestCase
{
	/**
	 * @var ElggUser
	 */
	private $testing_user;

	/**
	 * Class constructor.
	 *
	 * A simple wrapper to call the parent constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->testing_user = elgg_get_logged_in_user_entity();
	}

	/**
	 * Class destructor.
	 *
	 * The parent does not provide a destructor, so including an explicit one here.
	 */
	public function __destruct()
	{
	}

	/**
	 * "Log in" the given user. Does not call events/affect whether access is being ignored
	 *
	 * @todo make units not require this :)
	 *
	 * @param ElggUser $user
	 */
	protected function setLoggedInUser(ElggUser $user = null) {
		if ($user) {
			$_SESSION['user'] = $user;
		} else {
			unset($_SESSION['user']);
		}
		elgg_get_metadata_cache()->flush();
		global $ENTITY_CACHE;
		$ENTITY_CACHE = array();
		elgg_set_ignore_access(false);
	}

	/**
	 * "Log out" the given user. Does not call events.
	 */
	protected function setNotLoggedIn() {
		$this->setLoggedInUser();
	}

	/**
	 * Restore the original logged in user who's running the tests
	 */
	protected function restoreTestingUser() {
		$this->setLoggedInUser($this->testing_user);
	}
}
