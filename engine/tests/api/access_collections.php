<?php
/**
 * Access Collections tests
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreAccessCollectionsTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		parent::__construct();

		$this->dbPrefix = get_config("dbprefix");

		$user = new ElggUser();
		$user->username = 'test_user_' . rand();
		$user->email = 'fake_email@fake.com' . rand();
		$user->name = 'fake user';
		$user->access_id = ACCESS_PUBLIC;
		$user->salt = generate_random_cleartext_password();
		$user->password = generate_user_password($user, rand());
		$user->owner_guid = 0;
		$user->container_guid = 0;
		$user->save();

		$this->user = $user;
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {

	}

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
		// all __destruct() code should go above here
		$this->user->delete();
		parent::__destruct();
	}

	public function testCreateGetDeleteACL() {
		global $DB_QUERY_CACHE;
		
		$acl_name = 'test access collection';
		$acl_id = create_access_collection($acl_name);

		$this->assertTrue(is_int($acl_id));

		$q = "SELECT * FROM {$this->dbPrefix}access_collections WHERE id = $acl_id";
		$acl = get_data_row($q);

		$this->assertEqual($acl->id, $acl_id);

		if ($acl) {
			$DB_QUERY_CACHE = array();
			
			$this->assertEqual($acl->name, $acl_name);

			$result = delete_access_collection($acl_id);
			$this->assertTrue($result);

			$q = "SELECT * FROM {$this->dbPrefix}access_collections WHERE id = $acl_id";
			$data = get_data($q);
			$this->assertFalse($data);
		}
	}

	public function testAddRemoveUserToACL() {
		$acl_id = create_access_collection('test acl');

		$result = add_user_to_access_collection($this->user->guid, $acl_id);
		$this->assertTrue($result);

		if ($result) {
			$result = remove_user_from_access_collection($this->user->guid, $acl_id);
			$this->assertTrue($result);
		}

		delete_access_collection($acl_id);
	}

	public function testUpdateACL() {
		// another fake user to test with
		$user = new ElggUser();
		$user->username = 'test_user_' . rand();
		$user->email = 'fake_email@fake.com' . rand();
		$user->name = 'fake user';
		$user->access_id = ACCESS_PUBLIC;
		$user->salt = generate_random_cleartext_password();
		$user->password = generate_user_password($user, rand());
		$user->owner_guid = 0;
		$user->container_guid = 0;
		$user->save();

		$acl_id = create_access_collection('test acl');

		$member_lists = array(
			// adding
			array(
				$this->user->guid,
				$user->guid
			),
			// removing one, keeping one.
			array(
				$user->guid
			),
			// removing one, adding one
			array(
				$this->user->guid,
			),
			// removing all.
			array()
		);

		foreach ($member_lists as $members) {
			$result = update_access_collection($acl_id, $members);
			$this->assertTrue($result);

			if ($result) {
				$q = "SELECT * FROM {$this->dbPrefix}access_collection_membership
					WHERE access_collection_id = $acl_id";
				$data = get_data($q);

				if (count($members) == 0) {
					$this->assertFalse($data);
				} else {
					$this->assertEqual(count($members), count($data));
				}
				foreach ($data as $row) {
					$this->assertTrue(in_array($row->user_guid, $members));
				}
			}
		}

		delete_access_collection($acl_id);
		$user->delete();
	}

	// groups interface
	public function testNewGroupCreateACL() {

	}

	public function testDeleteGroupDeleteACL() {

	}

	public function testJoinGroupJoinACL() {

	}

	public function testLeaveGroupLeaveACL() {

	}
}
