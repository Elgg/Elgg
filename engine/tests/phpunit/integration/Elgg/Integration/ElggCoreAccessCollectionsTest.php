<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;
use ElggAccessCollection;

/**
 * Access Collections tests
 *
 * @group IntegrationTests
 * @group AccessCollections
 * @group Cache
 */
class ElggCoreAccessCollectionsTest extends IntegrationTestCase {


	private $dbprefix;

	/**
	 * @var \ElggUser
	 */
	protected $user;

	public function up() {
		$this->dbprefix = elgg_get_config("dbprefix");

		$user = $this->createUser();

		$this->user = $user;
	}

	public function down() {
		$this->user->delete();
	}

	public function testCreateGetDeleteACL() {

		$acl_name = 'test access collection';
		$acl_id = create_access_collection($acl_name);

		$this->assertTrue(is_int($acl_id));

		$q = "SELECT * FROM {$this->dbprefix}access_collections WHERE id = $acl_id";
		$acl = get_data_row($q);

		$this->assertEquals($acl_id, $acl->id);

		if ($acl) {
			$this->assertEquals($acl_name, $acl->name);

			$result = delete_access_collection($acl_id);
			$this->assertTrue($result);

			$q = "SELECT * FROM {$this->dbprefix}access_collections WHERE id = $acl_id";
			$data = get_data($q);
			$this->assertEquals([], $data);
		}
	}

	public function testAddRemoveUserToACL() {
		$acl_id = create_access_collection('test acl');

		$result = add_user_to_access_collection($this->user->guid, $acl_id);
		$this->assertTrue($result);

		if ($result) {
			$result = remove_user_from_access_collection($this->user->guid, $acl_id);
			$this->assertEquals(true, $result);
		}

		delete_access_collection($acl_id);
	}

	public function testUpdateACL() {
		// another fake user to test with
		$user = $this->createUser();

		$acl_id = create_access_collection('test acl');

		$member_lists = [
			// adding
			[
				$this->user->guid,
				$user->guid
			],
			// removing one, keeping one.
			[
				$user->guid
			],
			// removing one, adding one
			[
				$this->user->guid,
			],
			// removing all.
			[]
		];

		foreach ($member_lists as $members) {
			$result = update_access_collection($acl_id, $members);
			$this->assertTrue($result);

			if ($result) {
				$q = "SELECT * FROM {$this->dbprefix}access_collection_membership
					WHERE access_collection_id = $acl_id";

				$data = get_data($q);

				if (count($members) == 0) {
					$this->assertEmpty($data);
				} else {
					$this->assertEquals(count($members), count($data));
				}
				foreach ($data as $row) {
					$this->assertTrue(in_array($row->user_guid, $members));
				}
			}
		}

		delete_access_collection($acl_id);
		$user->delete();
	}

	public function testCanEditACL() {
		$acl_id = create_access_collection('test acl', $this->user->guid);

		_elgg_services()->session->setLoggedInUser($this->user);

		// should be true since it's the owner
		$result = can_edit_access_collection($acl_id, $this->user->guid);
		$this->assertTrue($result);

		// should be true since IA is on.
		$ia = elgg_set_ignore_access(true);
		$result = can_edit_access_collection($acl_id);
		$this->assertTrue($result);
		elgg_set_ignore_access($ia);

		_elgg_services()->session->removeLoggedInUser();

		$ia = elgg_set_ignore_access(false);
		$result = can_edit_access_collection($acl_id, $this->user->guid);
		$result_no_user = can_edit_access_collection($acl_id);
		$this->assertTrue($result);
		$this->assertFalse($result_no_user);
		elgg_set_ignore_access($ia);

		delete_access_collection($acl_id);
	}

	public function testAccessCaching() {
		// create a new user to check against
		$user = $this->createUser();

		_elgg_services()->session->setLoggedInUser($user);

		$id = create_access_collection('custom', $user->guid);

		foreach ([
					 'get_access_list',
					 'get_access_array'
				 ] as $func) {

			_elgg_services()->accessCache->clear();

			$expected = [
				ACCESS_PUBLIC,
				ACCESS_LOGGED_IN,
				ACCESS_PRIVATE,
				$id,
			];

			$actual = $func($user->getGUID());
			$this->assertNotEquals($expected, $actual);
		}

		$user->delete();

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testAddMemberToACLRemoveMember() {
		// create a new user to check against
		$user = $this->createUser();
		$owner = $this->createUser();

		$acl_id = create_access_collection('test acl', $owner->guid);
		$this->assertGreaterThan(0, $acl_id);

		$result = add_user_to_access_collection($user->guid, $acl_id);
		$this->assertTrue($result);

		// Adding again should have no result
		$result = add_user_to_access_collection($user->guid, $acl_id);
		$this->assertFalse($result);

		if ($result) {
			$this->assertTrue($user->delete());

			// since there are no more members this should return false
			$acl_members = get_members_of_access_collection($acl_id, true);
			$this->assertFalse($acl_members);
		}

		delete_access_collection($acl_id);
	}

	public function testCanGetGuestReadAcccessArray() {
		$expected = [ACCESS_PUBLIC];
		$actual = get_access_array();
		$this->assertEquals($expected, $actual);
	}

	public function testCanGetReadAccessArray() {

		// Default access array
		$expected = [
			ACCESS_PUBLIC,
			ACCESS_LOGGED_IN,
			elgg_get_private_access($this->user),
			elgg_get_friends_access($this->user),
		];

		$actual = get_access_array($this->user->guid);

		sort($expected);
		sort($actual);
		$this->assertEquals($expected, $actual);

		$owned_collection_id = create_access_collection('test', $this->user->guid);

		$joined_collection_id = create_access_collection('test2');
		add_user_to_access_collection($this->user->guid, $joined_collection_id);

		$expected[] = $owned_collection_id;
		$expected[] = $joined_collection_id;

		$actual = get_access_array($this->user->guid, null, true);

		sort($expected);
		sort($actual);
		$this->assertEquals($expected, $actual);

		$ia = elgg_set_ignore_access(true);

		$actual = get_access_array($this->user->guid, null, true);

		sort($expected);
		sort($actual);
		$this->assertEquals($expected, $actual);

		delete_access_collection($owned_collection_id);
		delete_access_collection($joined_collection_id);

		elgg_set_ignore_access($ia);
	}

	public function testCanGetWriteAccessArray() {

		$owned_collection_id = create_access_collection('test', $this->user->guid);

		$expected = [
			elgg_get_private_access($this->user),
			elgg_get_friends_access($this->user),
			$owned_collection_id,
			ACCESS_LOGGED_IN,
			ACCESS_PUBLIC,
		];

		$actual = get_write_access_array($this->user->guid, null, true);

		$actual = array_keys($actual);

		$this->assertEquals($expected, $actual);

	}

	public function testCanGetAccessCollection() {

		$owned_collection_id = create_access_collection('test', $this->user->guid);

		$acl = get_access_collection($owned_collection_id);

		$this->assertInstanceOf(ElggAccessCollection::class, $acl);
		$this->assertEquals($owned_collection_id, $acl->id);
		$this->assertEquals($this->user->guid, $acl->owner_guid);
		$this->assertEquals('test', $acl->name);

		// We don't add owners to collection by default
		$this->assertFalse($acl->hasMember($this->user->guid));

	}

	public function testCanSaveAccessCollection() {

		$id = create_access_collection('test_collection', $this->user->guid);

		$acl = new ElggAccessCollection((object) [
			'id' => $id,
			'owner_guid' => $this->user->guid,
			'name' => 'test_collection',
		]);

		$loaded_acl = get_access_collection($id);

		$this->assertEquals($id, $loaded_acl->id);
		$this->assertEquals('test_collection', $loaded_acl->name);
		$this->assertEquals($this->user->guid, $loaded_acl->owner_guid);

		$acl->name = 'test_collection_edited';
		$acl->save();

		$loaded_acl = get_access_collection($id);

		$this->assertEquals($id, $loaded_acl->id);
		$this->assertEquals('test_collection_edited', $loaded_acl->name);
		$this->assertEquals($this->user->guid, $loaded_acl->owner_guid);

		$this->assertTrue($acl->delete());

		$this->assertFalse(get_access_collection($id));
	}

	public function testCanUpdateAccessCollectionMembership() {

		$member1 = $this->createUser();
		$member2 = $this->createUser();

		$id = create_access_collection('test_collection', $this->user->guid);
		$acl = get_access_collection($id);

		$this->assertInstanceOf(ElggAccessCollection::class, $acl);

		$this->assertTrue($acl->addMember($member1->guid));
		$this->assertTrue($acl->addMember($member2->guid));

		$members = get_members_of_access_collection($id, true);

		$this->assertTrue(!empty($members));

		$this->assertTrue(in_array($member1->guid, $members));
		$this->assertTrue(in_array($member2->guid, $members));

		$acl->removeMember($member2->guid);

		$members = get_members_of_access_collection($id, true);

		$this->assertEquals([$member1->guid], $members);

		$this->assertTrue($acl->hasMember($member1->guid));

		$member1->delete();
		$member2->delete();
	}

	public function testCanEditAccessCollection() {

		$member = $this->createUser();

		$ia = elgg_set_ignore_access(false);

		$collection_id = create_access_collection('test', $this->user->guid);
		add_user_to_access_collection($member->guid, $collection_id);

		$acl = get_access_collection($collection_id);

		$this->assertTrue($acl->canEdit($this->user->guid));
		$this->assertFalse($acl->canEdit($member->guid));

		elgg_set_ignore_access($ia);

		$member->delete();
	}

}
