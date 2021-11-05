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
		$this->group = $this->createGroup();
		$this->user = $this->createUser();
	}

	public function testContentAccessMode() {
		$unrestricted = ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED;
		$membersonly = ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY;

		// if mode not set, open groups are unrestricted
		$this->assertEquals($unrestricted, $this->group->getContentAccessMode());

		// if mode not set, closed groups are membersonly
		unset($this->group->content_access_mode);
		$this->group->membership = ACCESS_PRIVATE;
		$this->assertEquals($membersonly, $this->group->getContentAccessMode());

		// test set
		$this->group->setContentAccessMode($unrestricted);
		$this->assertEquals($unrestricted, $this->group->getContentAccessMode());
		$this->group->setContentAccessMode($membersonly);
		$this->assertEquals($membersonly, $this->group->getContentAccessMode());
	}
}
