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
		$this->group->setPublicMembership(true);
		$this->group->save();
		$this->user = new ElggUser();
		$this->user->username = 'test_user_' . rand();
		$this->user->save();
	}

	public function testWalled() {
		// if walled not set, open groups are not walled
		$this->assertFalse($this->group->isWalled());

		// after first check, walled is set
		$this->assertEqual('no', $this->group->walled);

		// if walled not set, closed groups are walled
		$this->group->deleteMetadata('walled');
		$this->group->setPublicMembership(false);
		$this->assertTrue($this->group->isWalled());

		$this->group->setWalled(false);
		$this->assertFalse($this->group->isWalled());
		$this->group->setWalled(true);
		$this->assertTrue($this->group->isWalled());
	}

	public function testGroupGatekeeper() {
		// unwalled groups are open
		$this->group->setWalled(false);
		$this->assertTrue(group_gatekeeper(false, $this->group, $this->user));

		// walled group: non-members fail
		$this->group->setWalled(true);
		$this->assertFalse(group_gatekeeper(false, $this->group, $this->user));

		// admins succeed
		$this->assertTrue(group_gatekeeper(false, $this->group, elgg_get_logged_in_user_entity()));

		// members succeed
		$this->group->join($this->user);
		$this->assertTrue(group_gatekeeper(false, $this->group, $this->user));
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		$this->group->delete();
		$this->user->delete();
	}
}
