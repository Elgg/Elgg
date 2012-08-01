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
		$this->group->save();
		$this->user = new ElggUser();
		$this->user->username = 'test_user_' . rand();
		$this->user->save();
	}

	public function testGatekeeperMode() {
		$unrestricted = ElggGroup::GATEKEEPER_MODE_UNRESTRICTED;
		$membersonly = ElggGroup::GATEKEEPER_MODE_MEMBERSONLY;

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

	public function testGroupGatekeeper() {
		// unrestricted: pass non-members
		$this->group->setGatekeeperMode(ElggGroup::GATEKEEPER_MODE_UNRESTRICTED);
		$this->assertTrue(group_gatekeeper(false, $this->group, $this->user));

		// membersonly: non-members fail
		$this->group->setGatekeeperMode(ElggGroup::GATEKEEPER_MODE_MEMBERSONLY);
		$this->assertFalse(group_gatekeeper(false, $this->group, $this->user));

		// non-member admins succeed
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
