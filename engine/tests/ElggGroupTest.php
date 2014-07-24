<?php
/**
 * Elgg Test \ElggGroup
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreGroupTest extends \ElggCoreUnitTest {
	/**
	 * @var \ElggGroup
	 */
	protected $group;

	/**
	 * @var \ElggUser
	 */
	protected $user;

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->group = new \ElggGroup();
		$this->group->membership = ACCESS_PUBLIC;
		$this->group->access_id = ACCESS_PUBLIC;
		$this->group->save();
		$this->user = new \ElggUser();
		$this->user->username = 'test_user_' . rand();
		$this->user->save();
	}

	public function testContentAccessMode() {
		$unrestricted = \ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED;
		$membersonly = \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY;

		// if mode not set, open groups are unrestricted
		$this->assertEqual($this->group->getContentAccessMode(), $unrestricted);

		// after first check, metadata is set
		$this->assertEqual($this->group->content_access_mode, $unrestricted);

		// if mode not set, closed groups are membersonly
		$this->group->deleteMetadata('content_access_mode');
		$this->group->membership = ACCESS_PRIVATE;
		$this->assertEqual($this->group->getContentAccessMode(), $membersonly);

		// test set
		$this->group->setContentAccessMode($unrestricted);
		$this->assertEqual($this->group->getContentAccessMode(), $unrestricted);
		$this->group->setContentAccessMode($membersonly);
		$this->assertEqual($this->group->getContentAccessMode(), $membersonly);
	}

	public function testGroupItemVisibility() {
		$original_user = _elgg_services()->session->getLoggedInUser();
		_elgg_services()->session->setLoggedInUser($this->user);
		$group_guid = $this->group->guid;

		// unrestricted: pass non-members
		$this->group->setContentAccessMode(\ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED);
		$vis = \Elgg\GroupItemVisibility::factory($group_guid, false);

		$this->assertFalse($vis->shouldHideItems);

		// membersonly: non-members fail
		$this->group->setContentAccessMode(\ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY);
		$vis = \Elgg\GroupItemVisibility::factory($group_guid, false);

		$this->assertTrue($vis->shouldHideItems);

		// members succeed
		$this->group->join($this->user);
		$vis = \Elgg\GroupItemVisibility::factory($group_guid, false);

		$this->assertFalse($vis->shouldHideItems);

		// non-member admins succeed - assumes admin logged in
		_elgg_services()->session->setLoggedInUser($original_user);
		$vis = \Elgg\GroupItemVisibility::factory($group_guid, false);

		$this->assertFalse($vis->shouldHideItems);
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		$this->group->delete();
		$this->user->delete();
	}
}
