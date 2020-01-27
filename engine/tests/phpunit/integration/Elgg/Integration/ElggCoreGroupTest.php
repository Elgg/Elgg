<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;
use ElggGroup;

/**
 * Elgg Test \ElggGroup
 *
 * @group IntegrationTests
 */
class ElggCoreGroupTest extends IntegrationTestCase {
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
		
		elgg()->session->removeLoggedInUser();
	}

	public function testContentAccessMode() {
		$unrestricted = ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED;
		$membersonly = ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY;

		// if mode not set, open groups are unrestricted
		$this->assertEquals($this->group->getContentAccessMode(), $unrestricted);

		// if mode not set, closed groups are membersonly
		$this->group->membership = ACCESS_PRIVATE;
		$this->assertEquals($this->group->getContentAccessMode(), $membersonly);

		// test set
		$this->group->setContentAccessMode($unrestricted);
		$this->assertEquals($this->group->getContentAccessMode(), $unrestricted);
		$this->group->setContentAccessMode($membersonly);
		$this->assertEquals($this->group->getContentAccessMode(), $membersonly);
	}
}
