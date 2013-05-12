<?php
/**
 * Elgg Test ElggGroup
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreGroupTest extends ElggCoreUnitTest {
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

	public function testGatekeeperMode() {
		$unrestricted = ElggGroup::GATEKEEPER_MODE_UNRESTRICTED;
		$membersonly = ElggGroup::GATEKEEPER_MODE_MEMBERS_ONLY;

		// if mode not set, open groups are unrestricted
		$this->assertEqual($this->group->getGatekeeperMode(), $unrestricted);

		// after first check, metadata is set
		$this->assertEqual($this->group->gatekeeper_mode, $unrestricted);

		// if mode not set, closed groups are membersonly
		$this->group->deleteMetadata('gatekeeper_mode');
		$this->group->membership = ACCESS_PRIVATE;
		$this->assertEqual($this->group->getGatekeeperMode(), $membersonly);

		// test set
		$this->group->setGatekeeperMode($unrestricted);
		$this->assertEqual($this->group->getGatekeeperMode(), $unrestricted);
		$this->group->setGatekeeperMode($membersonly);
		$this->assertEqual($this->group->getGatekeeperMode(), $membersonly);
	}

	public function testGroupItemVisibility() {
		$original_user = _elgg_services()->session->get('user');
		_elgg_services()->session->set('user', $this->user);
		$group_guid = $this->group->guid;

		// unrestricted: pass non-members
		$this->group->setGatekeeperMode(ElggGroup::GATEKEEPER_MODE_UNRESTRICTED);
		$vis = Elgg_GroupItemVisibility::factory($group_guid, false);

		$this->assertFalse($vis->shouldHideItems);

		// membersonly: non-members fail
		$this->group->setGatekeeperMode(ElggGroup::GATEKEEPER_MODE_MEMBERS_ONLY);
		$vis = Elgg_GroupItemVisibility::factory($group_guid, false);

		$this->assertTrue($vis->shouldHideItems);

		// members succeed
		$this->group->join($this->user);
		$vis = Elgg_GroupItemVisibility::factory($group_guid, false);

		$this->assertFalse($vis->shouldHideItems);

		// non-member admins succeed - assumes admin logged in
		_elgg_services()->session->set('user', $original_user);
		$vis = Elgg_GroupItemVisibility::factory($group_guid, false);

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
