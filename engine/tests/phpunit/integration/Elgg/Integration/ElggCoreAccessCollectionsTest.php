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
	 * @var \ElggUser
	 */
	protected $user;

	public function up() {
		$this->user = $this->createUser();
		elgg()->session->setLoggedInUser($this->user);
	}

	public function testUpdateACL() {
		// another fake user to test with
		$user = $this->createUser();

		$acl = elgg_create_access_collection('test acl');
		$this->assertInstanceOf(\ElggAccessCollection::class, $acl);

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
			$result = _elgg_services()->accessCollections->update($acl->id, $members);
			$this->assertTrue($result);

			$select = Select::fromTable(AccessCollections::MEMBERSHIP_TABLE_NAME);
			$select->select('*')
				->where($select->compare('access_collection_id', '=', $acl->id, ELGG_VALUE_ID));

			$data = elgg()->db->getData($select);

			$this->assertCount(count($members), $data);

			foreach ($data as $row) {
				$this->assertTrue(in_array($row->user_guid, $members));
			}
		}
	}

	public function testAccessCaching() {
		$access_cache = _elgg_services()->accessCache;
		
		_elgg_services()->hooks->backup();
		_elgg_services()->events->backup();
		
		// need to have no hooks and no events for this test
		$user = $this->createUser();
		
		_elgg_services()->hooks->restore();
		_elgg_services()->events->restore();

		$hash = $user->guid . 'get_access_array';
		
		$this->assertEmpty($access_cache[$hash]);
		
		$acl = elgg_create_access_collection('custom', $user->guid);
		$this->assertInstanceOf(\ElggAccessCollection::class, $acl);

		$expected = [
			ACCESS_PUBLIC,
			ACCESS_LOGGED_IN,
			$acl->id,
		];

		$this->assertEquals($expected, elgg_get_access_array($user->guid));

		// check if exists in cache
		$this->assertEquals($expected, $access_cache[$hash]);
		
		$manipulated_access = $expected;
		$manipulated_access[] = 'foo';
		$access_cache[$hash] = $manipulated_access;
		$this->assertEquals($manipulated_access, $access_cache[$hash]);
		
		$this->assertEquals($manipulated_access, elgg_get_access_array($user->guid));
		
		// check flush logic
		$flushed_access = _elgg_services()->accessCollections->getAccessArray($user->guid, true);
		$this->assertNotEquals($manipulated_access, $flushed_access);
		$this->assertEquals($expected, $flushed_access);
	}

	public function testCanGetGuestReadAcccessArray() {
		$logged_in_user = _elgg_services()->session->getLoggedInUser();
		_elgg_services()->session->removeLoggedInUser();

		$actual = elgg_get_access_array();

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
			$actual = elgg_get_access_array($this->user->guid);
			
			sort($expected);
			sort($actual);
			$this->assertEquals($expected, $actual);
			
			$owned_collection = elgg_create_access_collection('test', $this->user->guid);
			$this->assertInstanceOf(\ElggAccessCollection::class, $owned_collection);
			
			$joined_collection = elgg_create_access_collection('test2');
			$this->assertInstanceOf(\ElggAccessCollection::class, $joined_collection);
			
			$owned_collection->addMember($this->user->guid);
			
			$expected[] = $owned_collection->id;
			$expected[] = $joined_collection->id;
			
			$actual = _elgg_services()->accessCollections->getAccessArray($this->user->guid, true);
			
			sort($expected);
			sort($actual);
			$this->assertEquals($expected, $actual);
			
			$this->assertTrue($owned_collection->delete());
			$this->assertTrue($joined_collection->delete());
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
		elgg_create_access_collection('test', $this->user->guid);

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

		$actual = elgg_get_write_access_array($this->user->guid, true);

		$actual = array_keys($actual);

		sort($expected);
		sort($actual);
		$this->assertEquals($expected, $actual);
	}

	public function testCanGetAccessCollection() {

		$acl = elgg_create_access_collection('test', $this->user->guid);

		$this->assertInstanceOf(\ElggAccessCollection::class, $acl);
		$this->assertGreaterThan(0, $acl->id);
		$this->assertEquals($this->user->guid, $acl->owner_guid);
		$this->assertEquals('test', $acl->name);

		// We don't add owners to collection by default
		$this->assertFalse($acl->hasMember($this->user->guid));
	}

	public function testCanSaveAccessCollection() {

		$loaded_acl = elgg_create_access_collection('test_collection', $this->user->guid);
		$this->assertInstanceOf(\ElggAccessCollection::class, $loaded_acl);

		$acl = new \ElggAccessCollection((object) [
			'id' => $loaded_acl->id,
			'owner_guid' => $this->user->guid,
			'name' => 'test_collection',
		]);

		$this->assertEquals('test_collection', $loaded_acl->name);
		$this->assertEquals($this->user->guid, $loaded_acl->owner_guid);

		$acl->name = 'test_collection_edited';
		$this->assertTrue($acl->save());

		$reloaded_acl = elgg_get_access_collection($loaded_acl->id);
		$this->assertInstanceOf(\ElggAccessCollection::class, $reloaded_acl);
		
		$this->assertEquals($loaded_acl->id, $reloaded_acl->id);
		$this->assertEquals('test_collection_edited', $reloaded_acl->name);
		$this->assertEquals($this->user->guid, $reloaded_acl->owner_guid);

		$this->assertTrue($acl->delete());

		$this->assertEmpty(elgg_get_access_collection($loaded_acl->id));
	}

	public function testCanUpdateAccessCollectionMembership() {

		$member1 = $this->createUser();
		$member2 = $this->createUser();

		$acl = elgg_create_access_collection('test_collection', $this->user->guid);
		$this->assertInstanceOf(\ElggAccessCollection::class, $acl);

		$this->assertTrue($acl->addMember($member1->guid));
		$this->assertTrue($acl->addMember($member2->guid));

		$members = $acl->getMembers([
			'callback' => function($row) {
				return (int) $row->guid;
			},
		]);

		$this->assertNotEmpty($members);

		$this->assertTrue(in_array($member1->guid, $members));
		$this->assertTrue(in_array($member2->guid, $members));

		$acl->removeMember($member2->guid);

		$members = $acl->getMembers([
			'callback' => function($row) {
				return (int) $row->guid;
			},
		]);

		$this->assertEquals([$member1->guid], $members);
		$this->assertTrue($acl->hasMember($member1->guid));
	}
}
