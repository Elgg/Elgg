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
		$user->salt = _elgg_generate_password_salt();
		$user->password = generate_user_password($user, rand());
		$user->owner_guid = 0;
		$user->container_guid = 0;
		$user->save();

		$this->user = $user;
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

		$acl_name = 'test access collection';
		$acl_id = create_access_collection($acl_name);

		$this->assertTrue(is_int($acl_id));

		$q = "SELECT * FROM {$this->dbPrefix}access_collections WHERE id = $acl_id";
		$acl = get_data_row($q);

		$this->assertEqual($acl->id, $acl_id);

		if ($acl) {
			$this->assertEqual($acl->name, $acl_name);

			$result = delete_access_collection($acl_id);
			$this->assertTrue($result);

			$q = "SELECT * FROM {$this->dbPrefix}access_collections WHERE id = $acl_id";
			$data = get_data($q);
			$this->assertIdentical(array(), $data);
		}
	}

	public function testAddRemoveUserToACL() {
		$acl_id = create_access_collection('test acl');

		$result = add_user_to_access_collection($this->user->guid, $acl_id);
		$this->assertTrue($result);

		if ($result) {
			$result = remove_user_from_access_collection($this->user->guid, $acl_id);
			$this->assertIdentical(true, $result);
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
		$user->salt = _elgg_generate_password_salt();
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

	public function testCanEditACL() {
		$acl_id = create_access_collection('test acl', $this->user->guid);

		// should be true since it's the owner
		$result = can_edit_access_collection($acl_id, $this->user->guid);
		$this->assertTrue($result);

		// should be true since IA is on.
		$ia = elgg_set_ignore_access(true);
		$result = can_edit_access_collection($acl_id);
		$this->assertTrue($result);
		elgg_set_ignore_access($ia);

		// should be false since IA is off
		$ia = elgg_set_ignore_access(false);
		$result = can_edit_access_collection($acl_id);
		$this->assertFalse($result);
		elgg_set_ignore_access($ia);

		delete_access_collection($acl_id);
	}

	public function testCanEditACLHook() {
		// if only we supported closures!
		global $acl_test_info;

		$acl_id = create_access_collection('test acl');

		$acl_test_info = array(
			'acl_id' => $acl_id,
			'user' => $this->user
		);

		function test_acl_access_hook($hook, $type, $value, $params) {
			global $acl_test_info;
			if ($params['user_id'] == $acl_test_info['user']->guid) {
				$acl = get_access_collection($acl_test_info['acl_id']);
				$value[$acl->id] = $acl->name;
			}

			return $value;
		}

		elgg_register_plugin_hook_handler('access:collections:write', 'all', 'test_acl_access_hook');

		// enable security since we usually run as admin
		$ia = elgg_set_ignore_access(false);
		$result = can_edit_access_collection($acl_id, $this->user->guid);
		$this->assertTrue($result);
		$ia = elgg_set_ignore_access($ia);

		elgg_unregister_plugin_hook_handler('access:collections:write', 'all', 'test_acl_access_hook');

		delete_access_collection($acl_id);
	}

	// groups interface
	// only runs if the groups plugin is enabled because implementation is split between
	// core and the plugin.
	public function testCreateDeleteGroupACL() {
		if (!elgg_is_active_plugin('groups')) {
			return;
		}

		$group = new ElggGroup();
		$group->name = 'Test group';
		$group->save();
		$acl = get_access_collection($group->group_acl);

		// ACLs are owned by groups
		$this->assertEqual($acl->owner_guid, $group->guid);

		// removing group and acl
		$this->assertTrue($group->delete());

		$acl = get_access_collection($group->group_acl);
		$this->assertFalse($acl);

		$group->delete();
	}

	public function testJoinLeaveGroupACL() {
		if (!elgg_is_active_plugin('groups')) {
			return;
		}

		$group = new ElggGroup();
		$group->name = 'Test group';
		$group->save();

		$result = $group->join($this->user);
		$this->assertTrue($result);

		// disable security since we run as admin
		$ia = elgg_set_ignore_access(false);

		// need to set the page owner to emulate being in a group context.
		// this is kinda hacky.
		elgg_set_page_owner_guid($group->getGUID());

		if ($result) {
			$can_edit = can_edit_access_collection($group->group_acl, $this->user->guid);
			$this->assertTrue($can_edit);
		}

		$result = $group->leave($this->user);
		$this->assertTrue($result);

		if ($result) {
			$can_edit = can_edit_access_collection($group->group_acl, $this->user->guid);
			$this->assertFalse($can_edit);
		}

		 elgg_set_ignore_access($ia);

		$group->delete();
	}

	public function testAccessCaching() {
		// create a new user to check against
		$user = new ElggUser();
		$user->username = 'access_test_user';
		$user->save();

		foreach (array('get_access_list', 'get_access_array') as $func) {
			$cache = _elgg_get_access_cache();
			$cache->clear();

			// admin users run tests, so disable access
			elgg_set_ignore_access(true);
			$access = $func($user->getGUID());

			elgg_set_ignore_access(false);
			$access2 = $func($user->getGUID());
			$this->assertNotEqual($access, $access2, "Access test for $func");
		}

		$user->delete();
	}
}
