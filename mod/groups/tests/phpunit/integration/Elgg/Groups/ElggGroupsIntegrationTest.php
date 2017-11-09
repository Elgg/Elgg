<?php

namespace Elgg\Groups;

/**
 * @group IntegrationTests
 */
class ElggGroupsIntegrationTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var \ElggGroup
	 */
	private $group;

	public function up() {
		if (!elgg_is_active_plugin('groups')) {
			$this->markTestSkipped();
		}

		$this->group = $this->createGroup();
	}

	public function down() {
		$this->group->delete();
	}

	public function testCanLoadGroup() {
		$this->markTestSkipped('Last action doesn\'t match due to delayed query execution');

		$groups = elgg_get_entities([
			'guids' => $this->group->guid,
			'limit' => 1,
		]);

		$this->assertEquals($this->group, $groups[0]);
	}

	public function testCanJoinGroup() {

		$user = $this->createUser();

		$this->assertTrue((bool) $this->group->join($user));
		$this->assertTrue((bool) $this->group->isMember($user));
		$this->assertTrue((bool) $this->group->leave($user));
		$this->assertFalse((bool) $this->group->isMember($user));

		$user->delete();
	}
}