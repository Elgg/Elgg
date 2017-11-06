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
}
