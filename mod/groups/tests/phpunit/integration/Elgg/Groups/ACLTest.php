<?php

namespace Elgg\Groups;

/**
 * @group IntegrationTests
 */
class ACLTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var \ElggGroup
	 */
	protected $group;

	/**
	 * @var \ElggUser
	 */
	protected $user;
	
	public function up() {
		if (!elgg_is_active_plugin('groups')) {
			$this->markTestSkipped();
		}

		$this->user = $this->createUser();
		$this->group = $this->createGroup();
	}

	public function down() {
		if ($this->group) {
			$this->group->delete();
		}
		if ($this->user) {
			$this->user->delete();
		}
		_elgg_services()->session->removeLoggedInUser();
	}

	public function testCreateDeleteGroupACL() {
		$acl = _groups_get_group_acl($this->group);

		$this->assertNotEmpty($acl);
		
		$acl_id = $acl->id;

		// ACLs are owned by groups
		$this->assertEquals($acl->owner_guid, $this->group->guid);

		// removing group and acl
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$this->assertTrue($this->group->delete());
		});

		$acl = get_access_collection($acl_id);
		$this->assertFalse($acl);
	}

	public function testJoinLeaveGroupACL() {

		$result = $this->group->join($this->user);
		$this->assertTrue($result);

		$result = $this->group->leave($this->user);
		$this->assertTrue($result);

		$acl = _groups_get_group_acl($this->group);

		// Members of the collection can't edit it
		$can_edit = can_edit_access_collection($acl->id, $this->user->guid);
		$this->assertFalse($can_edit);

		// Users that can edit collection's owner, can edit the collection
		$can_edit = can_edit_access_collection($acl->id, $this->group->owner_guid);
		$this->assertTrue($can_edit);
	}
	
	/**
	 * https://github.com/Elgg/Elgg/pull/6393
	 * Hook handlers for 'access:collections:write','all' hook should respect
	 * group's content access mode and container write permissions
	 */
	public function testWriteAccessArray() {
		$membersonly = \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY;
		$unrestricted = \ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED;

		$original_page_owner = elgg_get_page_owner_entity();
		elgg_set_page_owner_guid($this->group->guid);
		
		$new_user = $this->createUser();

		// User is not a member of the group
		// Member-only group
		$acl = _groups_get_group_acl($this->group);
		$this->assertNotEmpty($acl);
		
		$acl_id = $acl->id;
		
		$this->group->setContentAccessMode($membersonly);
		$write_access = get_write_access_array($new_user->guid, true);
		$this->assertArrayNotHasKey($acl_id, $write_access);

		// Unrestricted group
		$this->group->setContentAccessMode($unrestricted);
		$write_access = get_write_access_array($new_user->guid, true);
		$this->assertArrayNotHasKey($acl_id, $write_access);

		// User is a member (can write to container)
		$this->group->join($new_user);

		// Member-only group
		$this->group->setContentAccessMode($membersonly);
		$write_access = get_write_access_array($new_user->guid, true);
		$this->assertArrayHasKey($acl_id, $write_access);

		// Unrestricted group
		$this->group->setContentAccessMode($unrestricted);
		$write_access = get_write_access_array($new_user->guid, true);
		$this->assertArrayHasKey($acl_id, $write_access);

		$original_page_owner_guid = (elgg_instanceof($original_page_owner)) ? $original_page_owner->guid : 0;
		elgg_set_page_owner_guid($original_page_owner_guid);

	}
}