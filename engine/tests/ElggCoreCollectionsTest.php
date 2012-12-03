<?php
/**
 * Elgg Test collections API
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreCollectionsTest extends ElggCoreUnitTest {

	/**
	 * Called before each test method.
	 */
	public function setUp() {

	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {

	}

	public function testBasicApi() {
		$mgr = elgg_collections();
		$this->assertIsA($mgr, 'ElggCollectionManager');

		$name ='test_collection';
		$user = elgg_get_logged_in_user_entity();

		$this->assertFalse($mgr->exists($user, $name));
		$this->assertNull($mgr->fetch($user, $name));

		$this->assertFalse(ElggCollection::canSeeExistenceMetadata($user, $name));

		$coll = $mgr->create($user, $name);
		$this->assertIsA($coll, 'ElggCollection');
		$this->assertEqual($coll->getName(), $name);
		$this->assertEqual($coll->getEntityGuid(), $user->guid);
		$this->assertEqual($coll->access_id, ACCESS_PUBLIC);
		$this->assertTrue($coll->canEdit());

		$this->assertTrue($mgr->exists($user, $name));
		$this->assertTrue(ElggCollection::canSeeExistenceMetadata($user, $name));

		// fetch/create return existing collections
		$fetched_coll = $mgr->fetch($user, $name);
		$this->assertReference($coll, $fetched_coll);

		$fetched_coll = $mgr->create($user, $name);
		$this->assertReference($coll, $fetched_coll);

		// changing props
		$coll->access_id = ACCESS_PRIVATE;
		$this->assertEqual($coll->access_id, ACCESS_PRIVATE);

		// different owner, entity editor can still edit
		$this->assertTrue($coll->canEdit());

		$coll->delete();
		$this->assertTrue($coll->isDeleted());
		$this->assertFalse($coll->canEdit());
		$this->assertNull($coll->access_id);

		try {
			$coll->access_id = 1;
			$this->fail('writing props of deleted collection show throw');
		} catch (RuntimeException $e) {}

		$this->assertFalse(ElggCollection::canSeeExistenceMetadata($user, $name));
		$this->assertFalse($mgr->exists($user, $name));
		$this->assertNull($mgr->fetch($user, $name));

		// recreate, make sure get a fresh instance
		$new_coll = $mgr->create($user, $name);
		$this->assertIsA($new_coll, 'ElggCollection');
		$this->assertClone($new_coll, $coll);
		$new_coll->delete();
	}

	public function testEntityDeleteRemovesCollections() {
		$obj = new ElggObject();
		$obj->save();

		$name = 'test_collection';

		$mgr = elgg_collections();
		$mgr->create($obj, $name);
		$this->assertTrue($mgr->exists($obj, $name));

		$obj->delete();
		$this->assertFalse($mgr->exists($obj, $name));
	}

	public function testCollectionAccess() {
		$name = 'test_collection';

		$obj = new ElggObject();
		$obj->access_id = ACCESS_PUBLIC;
		$obj->save();

		$mgr = elgg_collections();
		$coll = $mgr->create($obj, $name);
		$coll->access_id = ACCESS_PRIVATE;

		// change perspective to new user
		$u1 = new ElggUser();
		$u1->username = md5(microtime());
		$u1->save();
		$this->setLoggedInUser($u1);
		$ia = elgg_set_ignore_access(false);

		// can see it exists but can't fetch
		$this->assertTrue($mgr->exists($obj, $name));
		$this->assertNull($mgr->fetch($obj, $name));

		$this->restoreTestingUser();
		elgg_set_ignore_access($ia);

		$this->assertNotNull($mgr->fetch($obj, $name));

		$obj->delete();
		$u1->delete();
	}

	public function testAccessor() {
		$user = elgg_get_logged_in_user_entity();
		$name = 'test_collection';
		$mgr = elgg_collections();
		$coll = $mgr->create($user, $name);

		$accessor = $coll->getAccessor();
		$this->assertIsA($accessor, 'ElggCollectionAccessor');

		$this->assertEqual($accessor->count(), 0);

		$accessor->push(1);
		$this->assertEqual($accessor->count(), 1);
		$this->assertTrue($accessor->hasAnyOf(1));

		$accessor->push(array(2, 3, 1, $user));
		$this->assertEqual($accessor->count(), 4);
		$this->assertTrue($accessor->hasAnyOf($user));
		$this->assertTrue($accessor->hasAnyOf($user->guid));

		$this->assertEqual($accessor->indexOf($user), 3);
		$this->assertFalse($accessor->indexOf($user->guid + 5));

		$accessor->remove(array($user, 1));
		$this->assertEqual($accessor->count(), 2);

		$accessor->removeAll();
		$this->assertEqual($accessor->count(), 0);

		$accessor->push(range(1, 5));
		$this->assertEqual($accessor->count(), 5);

		$accessor->removeFromBeginning(3);
		$this->assertEqual($accessor->count(), 2);
		$this->assertTrue($accessor->hasAllOf(array(4, 5)));

		$accessor->removeFromEnd();
		$this->assertEqual($accessor->count(), 1);
		$this->assertTrue($accessor->hasAnyOf(4));

		$accessor->removeAll();
		$this->assertEqual($accessor->count(), 0);

		$accessor->push(range(1, 5));
		$slice_tests = array(
			array(0, null,  range(1, 5)),
			array(0, 4,     range(1, 4)),
			array(0, -2,    range(1, 3)),
			array(2, null,  range(3, 5)),
			array(2, 4,     range(3, 5)),
			array(2, -2,    array(3)   ),
			array(-3, null, range(3, 5)),
			array(-3, 1,    array(3)   ),
			array(-3, -1,   range(3, 4)),
		);
		foreach ($slice_tests as $test) {
			$expected = $test[2];
			$returned = $accessor->slice($test[0], $test[1]);
			$this->assertEqual(
				$returned,
				$expected,
				"slice({$test[0]}, {$test[1]}) returned [" . implode(',', $returned) . "]");
		}

		$coll->delete();
	}
}
