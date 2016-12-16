<?php
/**
 * Test access collection creation
 */
class GroupsAccessCollectionsTest extends ElggCoreUnitTest {

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

	/**
	 * Ensure that user can join a private group
	 * @see https://github.com/Elgg/Elgg/issues/1926
	 */
	public function testUserCanJoinPrivateGroup() {

		// Test that access collection has been created
		$collection = get_access_collection($this->group->group_acl);
		$this->assertEqual($collection->owner_guid, $this->group->guid);

		$this->group->access_id = $this->group->group_acl;
		$this->group->save();

		$this->assertFalse(has_access_to_entity($this->group, $this->user));

		$logged_in = elgg_get_logged_in_user_entity();
		$group_url = $this->group->getURL();

		$session = elgg_get_session();
		$session->setLoggedInUser($this->user);

		$joined = $this->group->join($this->user);
		$this->assertTrue($joined);
		$this->assertTrue($this->group->isMember($this->user));
		$this->assertTrue(has_access_to_entity($this->group, $this->user));
		$this->assertEqual($this->group->getURL(), $group_url);
		$this->assertTrue($collection->hasMember($this->user->guid));

		$left = $this->group->leave($this->user);
		$this->assertTrue($left);
		$this->assertFalse($this->group->isMember($this->user));
		$this->assertFalse(has_access_to_entity($this->group, $this->user));
		$this->assertFalse($collection->hasMember($this->user->guid));

		$session->setLoggedInUser($logged_in);
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		$this->group->delete();
		$this->user->delete();
	}

}
