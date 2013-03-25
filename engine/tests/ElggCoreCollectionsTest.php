<?php
/**
 * Elgg Test collections API
 *
 * @todo separate these tests out more
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

	/**
	 * @todo split this up to test capabilities
	 */
	public function testBasicApi() {
		$svc = _elgg_services()->collections;
		$this->assertIsA($svc, 'Elgg_CollectionsService');

		$name ='test_collection';
		$user = elgg_get_logged_in_user_entity();

		$this->assertFalse($svc->exists($user, $name));
		$this->assertNull($svc->fetch($user, $name));

		$this->assertFalse(Elgg_Collection::canSeeExistenceMetadata($user, $name));

		$coll = $svc->create($user, $name);
		$this->assertIsA($coll, 'Elgg_Collection');
		$this->assertEqual($coll->getName(), $name);
		$this->assertEqual($coll->getEntityGuid(), $user->guid);
		$this->assertEqual($coll->access_id, ACCESS_PUBLIC);
		$this->assertTrue($coll->canEdit());

		$this->assertTrue($svc->exists($user, $name));
		$this->assertTrue(Elgg_Collection::canSeeExistenceMetadata($user, $name));

		// fetch/create return existing collections
		$fetched_coll = $svc->fetch($user, $name);
		$this->assertReference($coll, $fetched_coll);

		$fetched_coll = $svc->create($user, $name);
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

		$this->assertFalse(Elgg_Collection::canSeeExistenceMetadata($user, $name));
		$this->assertFalse($svc->exists($user, $name));
		$this->assertNull($svc->fetch($user, $name));

		// recreate, make sure get a fresh instance
		$new_coll = $svc->create($user, $name);
		$this->assertIsA($new_coll, 'Elgg_Collection');
		$this->assertClone($new_coll, $coll);
		$new_coll->delete();
	}

	public function testEntityDeleteRemovesCollections() {
		$obj = new ElggObject();
		$obj->save();

		$name = 'test_collection';

		$svc = _elgg_services()->collections;
		$svc->create($obj, $name);
		$this->assertTrue($svc->exists($obj, $name));

		$obj->delete();
		$this->assertFalse($svc->exists($obj, $name));
	}

	/**
	 * @todo split this up to test capabilities
	 */
	public function testCollectionAccess() {
		$name = 'test_collection';

		$obj = new ElggObject();
		$obj->access_id = ACCESS_PUBLIC;
		$obj->save();

		$svc = _elgg_services()->collections;
		$coll = $svc->create($obj, $name);
		$coll->access_id = ACCESS_PRIVATE;

		// change perspective to new user
		$u1 = new ElggUser();
		$u1->username = md5(microtime());
		$u1->save();
		$this->setLoggedInUser($u1);
		$ia = elgg_set_ignore_access(false);

		// can see it exists but can't fetch
		$this->assertTrue($svc->exists($obj, $name));
		$this->assertNull($svc->fetch($obj, $name));

		$this->restoreTestingUser();
		elgg_set_ignore_access($ia);

		$this->assertNotNull($svc->fetch($obj, $name));

		$obj->delete();
		$u1->delete();
	}

	/**
	 * @todo split this up to test capabilities
	 */
	public function testAccessor() {
		$user = elgg_get_logged_in_user_entity();
		$name = 'test_collection';
		$svc = _elgg_services()->collections;
		$coll = $svc->create($user, $name);

		$accessor = $coll->getAccessor();
		$this->assertIsA($accessor, 'Elgg_Collection_Accessor');

		// make sure non-collection relationships don't interfere (regression)
		add_entity_relationship($coll->getEntityGuid(), 'tmp_collection_test', elgg_get_site_entity()->guid);
		add_entity_relationship(elgg_get_site_entity()->guid, 'tmp_collection_test', $coll->getEntityGuid());

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

		$accessor->push(range(1, 6));
		$slice_tests = array(
			array(0, null,  range(1, 6)),
			array(0, 4,     range(1, 4)),
			array(0, -2,    range(1, 4)),
			array(2, null,  range(3, 6)),
			array(2, 2,     range(3, 4)),
			array(2, -2,    range(3, 4)),
			array(-3, null, range(4, 6)),
			array(-3, 1,    array(4)   ),
			array(-3, -1,   range(4, 5)),
		);
		foreach ($slice_tests as $test) {
			$expected = $test[2];
			$returned = $accessor->slice($test[0], $test[1]);
			$this->assertEqual(
				$returned,
				$expected,
				"slice({$test[0]}, {$test[1]}) returned [" . implode(',', $returned) . "]");
		}

		$accessor->moveAfter(2, 4);
		$this->assertEqual($accessor->slice(), array(1, 3, 4, 2, 5, 6));

		$accessor->moveBefore(5, 4);
		$this->assertEqual($accessor->slice(), array(1, 3, 5, 4, 2, 6));

		$accessor->moveBefore(5, 1);
		$this->assertEqual($accessor->slice(), array(5, 1, 3, 4, 2, 6));

		$this->assertFalse($accessor->moveAfter(4, 1));

		$accessor->rearrange(array(3, 4, 2, 6), array(6, 4, 3, 2));
		$this->assertEqual($accessor->slice(), array(5, 1, 6, 4, 3, 2));

		$accessor->rearrange(array(5, 1, 6, 4, 3, 2), array(1, 5, 6, 4, 3, 2));
		$this->assertEqual($accessor->slice(), array(1, 5, 6, 4, 3, 2));

		$coll->delete();
	}

	/**
	 * @todo split this up to test capabilities
	 */
	public function testQueryModifier() {
		$time = time() - 20;
		$objs = array();
		foreach (range(0, 9) as $i) {
			$obj = new ElggObject();
			$obj->subtype = 'testQueryModifier';
			$obj->save();

			// Note: MySQL is non-deterministic when sorting by duplicate values.
			// So if we use a bunch of test objects with the same time_created, we'll get
			// different orders depending on if we JOIN with the collection table. To test
			// real world conditions, we use test objects with distinct time_created.
			$obj->time_created = ($time + $i);
			$obj->save();
			$objs[] = $obj;
		}

		$all_objs = $this->mapGuids($objs);

		$user = elgg_get_logged_in_user_entity();
		$name = 'testQueryModifier';
		$svc = _elgg_services()->collections;
		$coll = $svc->create($user, $name);

		$coll_guids = array($all_objs[2], $all_objs[4]);
		$coll->getAccessor()->push($coll_guids);

		// selector
		$mod = new Elgg_Collection_QueryModifier($coll);
		$fetched_objs = elgg_get_entities($mod->getOptions(array(
			'type' => 'object',
			'subtype' => 'testQueryModifier',
		)));
		$expected = $coll_guids;
		$computed = $this->mapGuids($fetched_objs);
		$this->assertEqual($expected, $computed);

		// missing collection
		$mod = new Elgg_Collection_QueryModifier();
		$fetched_objs = elgg_get_entities($mod->getOptions(array(
			'type' => 'object',
			'subtype' => 'testQueryModifier',
		)));
		$expected = array();
		$computed = $this->mapGuids($fetched_objs);
		$this->assertEqual($expected, $computed);

		// sticky
		$mod = new Elgg_Collection_QueryModifier($coll);
		$mod->setModel(Elgg_Collection_QueryModifier::MODEL_STICKY);
		$fetched_objs = elgg_get_entities($mod->getOptions(array(
			'type' => 'object',
			'subtype' => 'testQueryModifier',
			'limit' => 5,
		)));
		$expected = array(
			$all_objs[4],
			$all_objs[2],
			$all_objs[9],
			$all_objs[8],
			$all_objs[7],
		);
		$computed = $this->mapGuids($fetched_objs);
		$this->assertEqual($expected, $computed);

		// missing for sticky
		$mod = new Elgg_Collection_QueryModifier();
		$mod->setModel(Elgg_Collection_QueryModifier::MODEL_STICKY);
		$fetched_objs = elgg_get_entities($mod->getOptions(array(
			'type' => 'object',
			'subtype' => 'testQueryModifier',
			'limit' => 3,
		)));
		$expected = array(
			$all_objs[9],
			$all_objs[8],
			$all_objs[7],
		);
		$computed = $this->mapGuids($fetched_objs);
		$this->assertEqual($expected, $computed);

		// filter
		$mod = new Elgg_Collection_QueryModifier($coll);
		$mod->setModel(Elgg_Collection_QueryModifier::MODEL_FILTER);
		$fetched_objs = elgg_get_entities($mod->getOptions(array(
			'type' => 'object',
			'subtype' => 'testQueryModifier',
			'limit' => 7,
		)));
		$expected = array(
			$all_objs[9],
			$all_objs[8],
			$all_objs[7],
			$all_objs[6],
			$all_objs[5],
			$all_objs[3],
			$all_objs[1],
		);
		$computed = $this->mapGuids($fetched_objs);
		$this->assertEqual($expected, $computed);

		// missing for filter
		$mod = new Elgg_Collection_QueryModifier();
		$mod->setModel(Elgg_Collection_QueryModifier::MODEL_FILTER);
		$fetched_objs = elgg_get_entities($mod->getOptions(array(
			'type' => 'object',
			'subtype' => 'testQueryModifier',
			'limit' => 8,
		)));
		$expected = array(
			$all_objs[9],
			$all_objs[8],
			$all_objs[7],
			$all_objs[6],
			$all_objs[5],
			$all_objs[4],
			$all_objs[3],
			$all_objs[2],
		);
		$computed = $this->mapGuids($fetched_objs);
		$this->assertEqual($expected, $computed);

		$coll->delete();
		foreach ($objs as $obj) {
			$obj->delete();
		}
	}

	/**
	 * @param ElggEntity[] $entities
	 * @return int[]
	 */
	protected function mapGuids($entities) {
		foreach ($entities as $i => $entity) {
			$entities[$i] = $entity->guid;
		}
		return $entities;
	}
}
