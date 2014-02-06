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

	public function testWriteAccessArray() {
		$membersonly = ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY;
		$unrestricted = ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED;

		$group_guid = $this->group->guid;
		$original_page_owner = elgg_get_page_owner_entity();
		elgg_set_page_owner_guid($group_guid);

		$this->group->setContentAccessMode($membersonly);

		$write_access = get_write_access_array($this->user->guid, $this->group->site_guid, true);
		$this->assertTrue(array_key_exists($this->group->group_acl, $write_access));

		$this->group->setContentAccessMode($unrestricted);

		$write_access = get_write_access_array($this->user->guid, $this->group->site_guid, true);
		$this->assertTrue(array_key_exists($this->group->group_acl, $write_access));

		elgg_set_page_owner_guid($original_page_owner);

	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		$this->group->delete();
		$this->user->delete();
	}

}
