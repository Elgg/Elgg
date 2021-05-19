<?php

namespace Elgg\Integration;

use Elgg\Database\AccessCollections;
use Elgg\Database\Select;
use Elgg\IntegrationTestCase;

/**
 * Access Collections tests
 *
 * @group IntegrationTests
 * @group AccessCollections
 * @group Cache
 */
class ElggCoreAccessCollectionsTest extends IntegrationTestCase {

	/**
	 * @var string
	 */
	protected $dbprefix;
	
	/**
	 * @var \ElggUser
	 */
	protected $user;

	public function up() {
		$this->dbprefix = elgg_get_config('dbprefix');
		$this->user = $this->createUser();
		elgg()->session->setLoggedInUser($this->user);
	}

	public function down() {
		$this->user->delete();
		elgg()->session->removeLoggedInUser();
	}

	public function testCreateGetDeleteACL() {

		$acl_name = 'test access collection';
		$acl_id = create_access_collection($acl_name);

		$this->assertTrue(is_int($acl_id));

		$select = Select::fromTable(AccessCollections::TABLE_NAME);
		$select->select('*')
			->where($select->compare('id', '=', $acl_id, ELGG_VALUE_ID));
		
		$acl = elgg()->db->getDataRow($select);

		$this->assertEquals((int) $acl->id, $acl_id);

		if ($acl) {
			$this->assertEquals($acl->name, $acl_name);

			$result = delete_access_collection($acl_id);
			$this->assertTrue($result);

			$data = elgg()->db->getData($select);
			$this->assertEquals([], $data);
		}
	}

	public function testAddRemoveUserToACL() {
		$acl_id = create_access_collection('test acl');

		$result = add_user_to_access_collection($this->user->guid, $acl_id);
		$this->assertTrue($result);

		if ($result) {
			$result = remove_user_from_access_collection($this->user->guid, $acl_id);
			$this->assertTrue($result);
		}

		delete_access_collection($acl_id);
	}

	public function testUpdateACL() {
		// another fake user to test with
		$user = $this->createOne('user');

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
			$result = _elgg_services()->accessCollections->update($acl_id, $members);
			$this->assertTrue($result);

			if ($result) {
				$select = Select::fromTable(AccessCollections::MEMBERSHIP_TABLE_NAME);
				$select->select('*')
					->where($select->compare('access_collection_id', '=', $acl_id, ELGG_VALUE_ID));
				
				$data = elgg()->db->getData($select);

				$this->assertEquals(count($members), count($data));
				
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

		// should be true since it's the owner
		$result = can_edit_access_collection($acl_id, $this->user->guid);
		$this->assertTrue($result);

		// should be true since IA is on.
		$new_user = $this->createUser();
		elgg()->session->setLoggedInUser($new_user);
		
		$result = elgg_call(ELGG_IGNORE_ACCESS, function() use ($acl_id) {
			return can_edit_access_collection($acl_id);
		});
		
		elgg()->session->removeLoggedInUser();
		$this->assertTrue($result);
		
		elgg_call(ELGG_ENFORCE_ACCESS, function() use ($acl_id) {
			$result = can_edit_access_collection($acl_id, $this->user->guid);
			$result_no_user = can_edit_access_collection($acl_id);
			$this->assertTrue($result);
			$this->assertFalse($result_no_user);
		});

		delete_access_collection($acl_id);
	}

	public function testCanEditACLHook() {
		$acl_id = create_access_collection('test acl');

		$acl_test_info = [
			'acl_id' => $acl_id,
			'user' => $this->user
		];

		$handler = function(\Elgg\Hook $hook) use ($acl_test_info) {
			$value = $hook->getValue();
			
			if ($hook->getParam('user_id') == $acl_test_info['user']->guid) {
				$acl = get_access_collection($acl_test_info['acl_id']);
				$value[$acl->id] = $acl->name;
			}

			return $value;
		};

		elgg_register_plugin_hook_handler('access:collections:write', 'all', $handler, 600);

		// enable security since we usually run as admin
		$result = can_edit_access_collection($acl_id, $this->user->guid);
		$this->assertTrue($result);
		
		elgg_unregister_plugin_hook_handler('access:collections:write', 'all', $handler);

		delete_access_collection($acl_id);
	}

	public function testAccessCaching() {
		$access_cache = _elgg_services()->accessCache;
		
		$user = $this->createOne('user');

		$hash = $user->guid . 'get_access_array';
		
		$this->assertEmpty($access_cache[$hash]);
		
		$id = create_access_collection('custom', $user->guid);

		$expected = [
			ACCESS_PUBLIC,
			ACCESS_LOGGED_IN,
			$id,
		];

		$this->assertEquals($expected, get_access_array($user->guid));

		// check if exists in cache
		$this->assertEquals($expected, $access_cache[$hash]);
		
		$manipulated_access = $expected;
		$manipulated_access[] = 'foo';
		$access_cache[$hash] = $manipulated_access;
		$this->assertEquals($manipulated_access, $access_cache[$hash]);
		
		$this->assertEquals($manipulated_access, get_access_array($user->guid));
		
		// check flush logic
		$flushed_access = _elgg_services()->accessCollections->getAccessArray($user->guid, true);
		$this->assertNotEquals($manipulated_access, $flushed_access);
		$this->assertEquals($expected, $flushed_access);
		
		$user->delete();
	}

	public function testAddMemberToACLRemoveMember() {
		// create a new user to check against
		$user = $this->createOne('user');

		$acl_id = create_access_collection('test acl');

		$result = add_user_to_access_collection($user->guid, $acl_id);
		$this->assertTrue($result);

		// now remove the user, this should remove him from the ACL
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($user) {
			$this->assertTrue($user->delete());
		});

		// since there are no more members this should return false
		$acl_members = get_members_of_access_collection($acl_id, true);
		$this->assertEmpty($acl_members);

		delete_access_collection($acl_id);
	}

	public function testCanGetGuestReadAcccessArray() {
		$logged_in_user = _elgg_services()->session->getLoggedInUser();
		_elgg_services()->session->removeLoggedInUser();

		$actual = get_access_array();

		$this->assertTrue(in_array(ACCESS_PUBLIC, $actual)); // Public access needs to be allowed
		$this->assertFalse(in_array(ACCESS_LOGGED_IN, $actual)); // Logged in access needs to be prohibited
		$this->assertFalse(in_array(ACCESS_PRIVATE, $actual)); // Private access needs to be prohibited

		if ($logged_in_user instanceof \ElggUser){
			_elgg_services()->session->setLoggedInUser($logged_in_user);
		}
	}

	public function testCanGetReadAccessArray() {
		// Default access array
		$expected = [
			ACCESS_PUBLIC,
			ACCESS_LOGGED_IN,
		];
		
		// get owned access collections
		$collections = $this->user->getOwnedAccessCollections();
		if (!empty($collections)) {
			foreach ($collections as $acl) {
				$expected[] = (int) $acl->id;
			}
		}
			
		elgg_call(ELGG_ENFORCE_ACCESS, function() use ($expected) {
			
			$actual = get_access_array($this->user->guid);
	
			sort($expected);
			sort($actual);
			$this->assertEquals($expected, $actual);
	
			$owned_collection_id = create_access_collection('test', $this->user->guid);
	
			$joined_collection_id = create_access_collection('test2');
			add_user_to_access_collection($this->user->guid, $joined_collection_id);
	
			$expected[] = $owned_collection_id;
			$expected[] = $joined_collection_id;
	
			$actual = _elgg_services()->accessCollections->getAccessArray($this->user->guid, true);
	
			sort($expected);
			sort($actual);
			$this->assertEquals($expected, $actual);
			
			delete_access_collection($owned_collection_id);
			delete_access_collection($joined_collection_id);
		});

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($expected) {
			$expected[] = ACCESS_PRIVATE;
			$actual = _elgg_services()->accessCollections->getAccessArray($this->user->guid, true);
	
			sort($expected);
			sort($actual);
			$this->assertEquals($expected, $actual);
		});
	}

	public function testCanGetWriteAccessArray() {
		create_access_collection('test', $this->user->guid);

		$expected = [
			ACCESS_LOGGED_IN,
			ACCESS_PRIVATE,
		];

		// is the test site running in walled garden?
		if (!elgg_get_config('walled_garden')) {
			$expected[] = ACCESS_PUBLIC;
		}

		// get owned access collections
		$collections = $this->user->getOwnedAccessCollections();
		if (!empty($collections)) {
			foreach ($collections as $acl) {
				$expected[] = (int) $acl->id;
			}
		}

		$actual = get_write_access_array($this->user->guid, null, true);

		$actual = array_keys($actual);

		sort($expected);
		sort($actual);
		$this->assertEquals($expected, $actual);
	}

	public function testCanGetAccessCollection() {

		$owned_collection_id = create_access_collection('test', $this->user->guid);

		$acl = get_access_collection($owned_collection_id);

		$this->assertInstanceOf(\ElggAccessCollection::class, $acl);
		$this->assertEquals($owned_collection_id, $acl->id);
		$this->assertEquals($this->user->guid, $acl->owner_guid);
		$this->assertEquals('test', $acl->name);

		// We don't add owners to collection by default
		$this->assertFalse($acl->hasMember($this->user->guid));
	}

	public function testCanSaveAccessCollection() {

		$id = create_access_collection('test_collection', $this->user->guid);

		$acl = new \ElggAccessCollection((object) [
			'id' => $id,
			'owner_guid' => $this->user->guid,
			'name' => 'test_collection',
		]);

		$loaded_acl = get_access_collection($id);

		$this->assertEquals($id, $loaded_acl->id);
		$this->assertEquals('test_collection', $loaded_acl->name);
		$this->assertEquals($this->user->guid, $loaded_acl->owner_guid);

		$acl->name = 'test_collection_edited';
		$this->assertTrue($acl->save());

		$loaded_acl = get_access_collection($id);

		$this->assertEquals($id, $loaded_acl->id);
		$this->assertEquals('test_collection_edited', $loaded_acl->name);
		$this->assertEquals($this->user->guid, $loaded_acl->owner_guid);

		$this->assertTrue($acl->delete());

		$this->assertFalse(get_access_collection($id));
	}

	public function testCanUpdateAccessCollectionMembership() {

		$member1 = $this->createOne('user');
		$member2 = $this->createOne('user');

		$id = create_access_collection('test_collection', $this->user->guid);
		$acl = get_access_collection($id);

		$this->assertInstanceOf(\ElggAccessCollection::class, $acl);

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

		$member = $this->createOne('user');

		elgg_call(ELGG_ENFORCE_ACCESS, function() use ($member) {
			$collection_id = create_access_collection('test', $this->user->guid);
			add_user_to_access_collection($member->guid, $collection_id);
	
			$acl = get_access_collection($collection_id);
	
			$this->assertTrue($acl->canEdit($this->user->guid));
			$this->assertFalse($acl->canEdit($member->guid));
		});
		
		$member->delete();
	}

}
