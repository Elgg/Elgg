<?php
/**
 * Test write access arrays
 */
class GroupsWriteAccessTest extends ElggCoreUnitTest {

	/**
	 * @var ElggGroup
	 */
	protected $group;

	/**
	 * @var ElggUser
	 */
	protected $user;

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->group = new ElggGroup();
		$this->group->membership = ACCESS_PUBLIC;
		$this->group->access_id = ACCESS_PUBLIC;
		$this->group->save();
		$this->user = new ElggUser();
		$this->user->username = 'test_user_' . rand();
		$this->user->save();
	}

	/**
	 * https://github.com/Elgg/Elgg/pull/6393
	 * Hook handlers for 'access:collections:write','all' hook should respect
	 * group's content access mode and container write permissions
	 */
	public function testWriteAccessArray() {
		$membersonly = ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY;
		$unrestricted = ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED;

		$original_page_owner = elgg_get_page_owner_entity();
		elgg_set_page_owner_guid($this->group->guid);

		$ia = elgg_set_ignore_access(false);

		// User is not a member of the group
		// Member-only group
		$this->group->setContentAccessMode($membersonly);
		$write_access = get_write_access_array($this->user->guid, $this->group->site_guid, true);
		$this->assertFalse(array_key_exists($this->group->group_acl, $write_access));
		// Unrestricted group
		$this->group->setContentAccessMode($unrestricted);
		$write_access = get_write_access_array($this->user->guid, $this->group->site_guid, true);
		$this->assertFalse(array_key_exists($this->group->group_acl, $write_access));

		// User is a member (can write to container)
		$this->group->join($this->user);

		// Member-only group
		$this->group->setContentAccessMode($membersonly);
		$write_access = get_write_access_array($this->user->guid, $this->group->site_guid, true);
		$this->assertTrue(array_key_exists($this->group->group_acl, $write_access));
		// Unrestricted group
		$this->group->setContentAccessMode($unrestricted);
		$write_access = get_write_access_array($this->user->guid, $this->group->site_guid, true);
		$this->assertTrue(array_key_exists($this->group->group_acl, $write_access));

		elgg_set_ignore_access($ia);

		$this->group->leave($this->user);

		$original_page_owner_guid = (elgg_instanceof($original_page_owner)) ? $original_page_owner->guid : 0;
		elgg_set_page_owner_guid($original_page_owner_guid);

	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		$this->group->delete();
		$this->user->delete();
	}

}
