<?php
/**
 * Access SQL tests
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreAccessSQLTest extends ElggCoreUnitTest {
	/**
	 * Called before each test object.
	 */
	public function __construct() {
		parent::__construct();

		$this->dbPrefix = get_config("dbprefix");

		$this->objects = array();
		$this->users = array();

		$this->access_array = array(
			ACCESS_PRIVATE,
			ACCESS_FRIENDS,
			ACCESS_LOGGED_IN,
			ACCESS_PUBLIC
		);

		// Create a couple users and create objects
		for ($i=0; $i<2; $i++) {
			$user = new ElggUser();
			$user->username = 'test_user_' . rand();
			$user->email = 'fake_email@fake.com' . rand();
			$user->name = 'fake user ' . rand();
			$user->access_id = ACCESS_PUBLIC;
			$user->salt = generate_random_cleartext_password();
			$user->password = generate_user_password($user, rand());
			$user->owner_guid = 0;
			$user->container_guid = 0;
			$user->save();
			$this->users[] = $user;

			foreach($this->access_array as $access) {
				$object = new ElggObject();
				$object->access_id = $access;
				$object->owner_guid = $user->guid;
				$object->container_guid = $user->guid;
				$object->save();

				$this->objects[$user->guid][$access] = $object;
			}	
		}
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		// do not allow SimpleTest to interpret Elgg notices as exceptions
		$this->swallowErrors();
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		// Delete users/objects
		foreach ($this->users as $user) {
			$user->delete();
		}
		// all __destruct() code should go above here
		parent::__destruct();
	}

	/**
	 * Test core access ids
	 */
	public function testAccessIDs() {
		$user_one = $this->users[0];		
		$user_two = $this->users[1];

		// Log in test user
		$admin = elgg_get_logged_in_user_entity();
		$_SESSION['user'] = $user_one;

		foreach ($this->access_array as $access) {
			// User can access their own objects regardless of access level
			$this->assertTrue(has_access_to_entity($this->objects[$user_one->guid][$access], $user_one));
		
			// Test access to other user's objects
			switch ($access) {
				case ACCESS_PRIVATE:
					// Can't access other user's private object
					$this->assertFalse(has_access_to_entity($this->objects[$user_two->guid][$access], $user_one));
					break;
				case ACCESS_FRIENDS:
					// Not friend's, can't access
					$this->assertFalse(has_access_to_entity($this->objects[$user_two->guid][$access], $user_one));
					
					// Make friends
					add_entity_relationship($user_two->guid, "friend", $user_one->guid);

					// Friends relationship exists, user now has access
					$this->assertTrue(has_access_to_entity($this->objects[$user_two->guid][$access], $user_one));

					// Clean up
					remove_entity_relationship($user_two->guid, "friend", $user_one->guid);
					break;
				case ACCESS_LOGGED_IN:
				case ACCESS_PUBLIC:
					// Check access to logged in/public
					$this->assertTrue(has_access_to_entity($this->objects[$user_two->guid][$access], $user_one));
					break;
			}
		}

		$_SESSION['user'] = $admin;
	}

	public function testGetAccessSqlSuffixHook() {
		// test hook
		function access_get_sql_suffix_test_hook($hook, $type, $value, $params) {
			if (elgg_is_logged_in() && @elgg_get_logged_in_user_entity()->is_superuser) {
				$value['ors'][] = "1 = 1";
			}
			return $value;
		}

		// Set some metadata on the user
		$this->users[0]->is_superuser = 1;
		$this->users[0]->save();

		// Register hook handler
		elgg_register_plugin_hook_handler('access:get_sql_suffix', 'user', 'access_get_sql_suffix_test_hook');

		$user_one = $this->users[0];		
		$user_two = $this->users[1];

		// Log in test user
		$admin = elgg_get_logged_in_user_entity();
		$_SESSION['user'] = $user_one;

		foreach ($this->access_array as $access) {
			// Can access other user's entities regardless of access_id
			$this->assertTrue(has_access_to_entity($this->objects[$user_two->guid][$access], $user_one));
		}

		$_SESSION['user'] = $admin;
	}
}