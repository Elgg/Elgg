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

	public function up() {
		$this->group = $this->createGroup([], ['membership' => ACCESS_PUBLIC]);
		$this->user = $this->createUser();
	}

	public function down() {
		$this->group->delete();
		$this->user->delete();
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
		$original_user = elgg_get_session()->getLoggedInUser();
		elgg_get_session()->setLoggedInUser($this->user);
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
		elgg_get_session()->setLoggedInUser($original_user);
		$vis = \Elgg\GroupItemVisibility::factory($group_guid, false);

		$this->assertFalse($vis->shouldHideItems);
	}
}
