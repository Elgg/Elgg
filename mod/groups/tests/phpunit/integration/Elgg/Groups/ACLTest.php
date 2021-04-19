<?php

namespace Elgg\Groups;

use Elgg\Plugins\PluginTesting;

/**
 * @group IntegrationTests
 */
class ACLTest extends \Elgg\IntegrationTestCase {

	use PluginTesting;
	
	/**
	 * @var \ElggGroup
	 */
	protected $group;

	/**
	 * @var \ElggUser
	 */
	protected $user;
	
	public function up() {
		$this->user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($this->user);
		
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
		$acl = $this->group->getOwnedAccessCollection('group_acl');

		$this->assertInstanceof(\ElggAccessCollection::class, $acl);
		
		$acl_id = $acl->id;

		// ACLs are owned by groups
		$this->assertEquals($acl->owner_guid, $this->group->guid);

		// removing group and acl
		$this->assertTrue($this->group->delete());

		$acl = get_access_collection($acl_id);
		$this->assertFalse($acl);
	}

	public function testJoinLeaveGroupACL() {
		
		$group = new \ElggGroup();
		$group->name = 'Test group';
		$group->access_id = ACCESS_PUBLIC;
		$group->save();

		$result = $group->join($this->user);
		$this->assertTrue($result);

		// disable security since we run as admin
		elgg_call(ELGG_ENFORCE_ACCESS, function() use ($group) {
			$result = $group->leave($this->user);
			$this->assertTrue($result);
	
			if ($result) {
				$acl = $group->getOwnedAccessCollection('group_acl');
				$can_edit = true;
				if ($acl instanceof \ElggAccessCollection) {
					$can_edit = can_edit_access_collection($acl->id, $this->user->guid);
				}
				$this->assertFalse($can_edit);
			}
		});
		
		$group->delete();

		$this->markTestIncomplete("Verify what was the intention with editing access collections");
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
		$this->assertInstanceOf(\ElggUser::class, $new_user);

		elgg_call(ELGG_ENFORCE_ACCESS, function() use ($new_user, $membersonly, $unrestricted) {
			// User is not a member of the group
			// Member-only group
			$acl = $this->group->getOwnedAccessCollection('group_acl');
			$this->assertInstanceOf(\ElggAccessCollection::class, $acl);
			
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
		});
		
		$this->group->leave($new_user);
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($new_user) {
			$this->assertTrue($new_user->delete());
		});
		
		$original_page_owner_guid = ($original_page_owner instanceof \ElggEntity) ? $original_page_owner->guid : 0;
		elgg_set_page_owner_guid($original_page_owner_guid);

	}
}
