<?php

namespace Elgg\Integration;

use Elgg\GroupItemVisibility;
use Elgg\LegacyIntegrationTestCase;
use ElggGroup;

/**
 * Elgg Test \ElggGroup
 *
 * @group IntegrationTests
 * @subpackage Test
 */
class ElggCoreGroupTest extends LegacyIntegrationTestCase {
	/**
	 * @var ElggGroup
	 */
	protected $group;

	/**
	 * @var \ElggUser
	 */
	protected $user;

	public function up() {
		$this->group = new ElggGroup();
		$this->group->membership = ACCESS_PUBLIC;
		$this->group->access_id = ACCESS_PUBLIC;
		$this->group->save();

		$this->user = $this->createOne('user');
	}

	public function down() {
		$this->group->delete();
		$this->user->delete();
	}

	public function testContentAccessMode() {
		$unrestricted = ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED;
		$membersonly = ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY;

		// if mode not set, open groups are unrestricted
		$this->assertEqual($this->group->getContentAccessMode(), $unrestricted);

		// if mode not set, closed groups are membersonly
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
		$this->group->setContentAccessMode(ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED);
		$vis = GroupItemVisibility::factory($group_guid, false);

		$this->assertFalse($vis->shouldHideItems);

		// membersonly: non-members fail
		$this->group->setContentAccessMode(ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY);
		$vis = GroupItemVisibility::factory($group_guid, false);

		$this->assertTrue($vis->shouldHideItems);

		// members succeed
		$this->group->join($this->user);
		$vis = GroupItemVisibility::factory($group_guid, false);

		$this->assertFalse($vis->shouldHideItems);

		// non-member admins succeed - assumes admin logged in
		_elgg_services()->session->setLoggedInUser($original_user);
		$vis = GroupItemVisibility::factory($group_guid, false);

		$this->assertFalse($vis->shouldHideItems);
	}
}
