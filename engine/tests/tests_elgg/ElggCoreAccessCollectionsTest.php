<?php
/**
 *  Copyright (C) 2011 Quanbit Software S.A.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  
 *  IMPORTANT: The tests in this file were ported from the original
 *  elgg engine tests. Please see Elgg's README.txt, COPYRIGHT.txt 
 *  and CONTRIBUTORS.txt for copyright and contributor information.  
 */
require_once(dirname(__FILE__) . '/../model/ElggTestCase.php');

class ElggCoreAccessCollectionsTest extends ElggTestCase
{
	protected $user;
	protected $dbPrefix;
	
	/**
	 * Called before each test method.
	 */
	public function setUp() 
	{
		parent::setUp();
		
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

	public function testCreateGetDeleteACL() 
	{
		global $DB_QUERY_CACHE;
		
		$acl_name = 'test access collection';
		$acl_id = create_access_collection($acl_name);

		$this->assertTrue(is_int($acl_id));

		$q = "SELECT * FROM {$this->dbPrefix}access_collections WHERE id = $acl_id";
		$acl = get_data_row($q);

		$this->assertEquals($acl->id, $acl_id);

		if ($acl) 
		{
			$DB_QUERY_CACHE = array();
			
			$this->assertEquals($acl->name, $acl_name);

			$result = delete_access_collection($acl_id);
			$this->assertTrue($result);

			$q = "SELECT * FROM {$this->dbPrefix}access_collections WHERE id = $acl_id";
			$data = get_data($q);
			$this->assertEquals($data, array());
		}
	}

	public function testAddRemoveUserToACL() 
	{
		$acl_id = create_access_collection('test acl');

		$result = add_user_to_access_collection($this->user->guid, $acl_id);
		$this->assertTrue($result);

		if ($result) 
		{
			$result = remove_user_from_access_collection($this->user->guid, $acl_id);
			$this->assertTrue($result);
		}
	}

	public function testUpdateACL() 
	{
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

		foreach ($member_lists as $members) 
		{
			$result = update_access_collection($acl_id, $members);
			$this->assertTrue($result);

			if ($result) 
			{
				$q = "SELECT * FROM {$this->dbPrefix}access_collection_membership
					WHERE access_collection_id = $acl_id";
				$data = get_data($q);

				if (count($members) == 0) 
				{
					$this->assertEquals(array(), $data);
				} 
				else 
					{
						$this->assertEquals(count($members), count($data));
					}
				foreach ($data as $row) 
				{
					$this->assertTrue(in_array($row->user_guid, $members));
				}
			}
		}
	}

	public function testCanEditACL() 
	{
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
	}

	public function testCanEditACLHook() 
	{
		// if only we supported closures!
		global $acl_test_info;

		$acl_id = create_access_collection('test acl');

		$acl_test_info = array(
								'acl_id' => $acl_id,
								'user' => $this->user
								);
		
		function test_acl_access_hook($hook, $type, $value, $params) 
		{
			global $acl_test_info;
			if ($params['user_id'] == $acl_test_info['user']->guid) 
			{
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
	}

	// groups interface
	// only runs if the groups plugin is enabled because implementation is split between
	// core and the plugin.
	public function testCreateDeleteGroupACL() 
	{
		if (!elgg_is_active_plugin('groups')) 
		{
			return;
		}
		
		$group = new ElggGroup();
		$group->name = 'Test group';
		$group->save();
		$acl = get_access_collection($group->group_acl);

		// ACLs are owned by groups
		$this->assertEquals($acl->owner_guid, $group->guid);

		// removing group and acl
		$this->assertTrue($group->delete());
		
		$acl = get_access_collection($group->group_acl);
		//Test fails due to a missing casting;
		//Should it be false or array()?
		//$this->assertEquals(array(), $acl);
		$this->assertFalse($acl);
	}

	public function testJoinLeaveGroupACL() 
	{
		if (!elgg_is_active_plugin('groups')) 
		{
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

		if ($result) 
		{
			$can_edit = can_edit_access_collection($group->group_acl, $this->user->guid);
			$this->assertTrue($can_edit);
		}

		$result = $group->leave($this->user);
		$this->assertTrue($result);

		if ($result) 
		{
			$can_edit = can_edit_access_collection($group->group_acl, $this->user->guid);
			$this->assertFalse($can_edit);
		}
		 elgg_set_ignore_access($ia);
	}
}