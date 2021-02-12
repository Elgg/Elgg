<?php

namespace Elgg\Integration;

use ElggObject;

/**
 * Test elgg_get_entities() with relationship options and
 * elgg_get_entities_from_relationship_count()
 *
 * @group IntegrationTests
 * @group Entities
 * @group EntityRelationships
 */
class ElggCoreGetEntitiesFromRelationshipTest extends ElggCoreGetEntitiesBaseTest {

	// Make sure metadata doesn't affect getting entities by relationship.  See #2274
	public function testElggApiGettersEntityRelationshipWithMetadata() {
		$guids = [];

		$obj1 = new ElggObject();
		$obj1->setSubtype($this->getRandomSubtype());
		$obj1->test_md = 'test';
		$obj1->save();
		$guids[] = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->setSubtype($this->getRandomSubtype());
		$obj2->test_md = 'test';
		$obj2->save();
		$guids[] = $obj2->guid;

		add_entity_relationship($guids[0], 'test', $guids[1]);

		$es = elgg_get_entities([
			'relationship' => 'test',
			'relationship_guid' => $guids[0],
		]);
		$this->assertIsArray($es);
		$this->assertCount(1, $es);

		foreach ($es as $e) {
			$this->assertEquals($e->guid, $guids[1]);
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	public function testElggApiGettersEntityRelationshipWithOutMetadata() {
		$guids = [];

		$obj1 = new ElggObject();
		$obj1->setSubtype($this->getRandomSubtype());
		$obj1->save();
		$guids[] = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->setSubtype($this->getRandomSubtype());
		$obj2->save();
		$guids[] = $obj2->guid;

		add_entity_relationship($guids[0], 'test', $guids[1]);

		$es = elgg_get_entities([
			'relationship' => 'test',
			'relationship_guid' => $guids[0]
		]);
		$this->assertIsArray($es);
		$this->assertCount(1, $es);

		foreach ($es as $e) {
			$this->assertEquals($e->guid, $guids[1]);
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	public function testElggApiGettersEntityRelationshipWithMetadataIncludingRealMetadata() {
		$guids = [];

		$obj1 = new ElggObject();
		$obj1->setSubtype($this->getRandomSubtype());
		$obj1->test_md = 'test';
		$obj1->save();
		$guids[] = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->setSubtype($this->getRandomSubtype());
		$obj2->test_md = 'test';
		$obj2->save();
		$guids[] = $obj2->guid;

		add_entity_relationship($guids[0], 'test', $guids[1]);

		$es = elgg_get_entities([
			'relationship' => 'test',
			'relationship_guid' => $guids[0],
			'metadata_name' => 'test_md',
			'metadata_value' => 'test',
		]);
		$this->assertIsArray($es);
		$this->assertCount(1, $es);

		foreach ($es as $e) {
			$this->assertEquals($e->guid, $guids[1]);
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	public function testElggApiGettersEntityRelationshipWithMetadataIncludingFakeMetadata() {
		$guids = [];

		$obj1 = new ElggObject();
		$obj1->setSubtype($this->getRandomSubtype());
		$obj1->test_md = 'test';
		$obj1->save();
		$guids[] = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->setSubtype($this->getRandomSubtype());
		$obj2->test_md = 'test';
		$obj2->save();
		$guids[] = $obj2->guid;

		add_entity_relationship($guids[0], 'test', $guids[1]);

		$es = elgg_get_entities([
			'relationship' => 'test',
			'relationship_guid' => $guids[0],
			'metadata_name' => 'test_md',
			'metadata_value' => 'invalid',
		]);

		$this->assertEmpty($es);

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	public function testElggGetEntitiesFromRelationshipCount() {
		$entities = $this->createMany(['user', 'object', 'group'], 2);
		$relationships = [];
		$count = count($entities);
		$max = $count - 1;
		$relationship_name = 'test_relationship_' . rand(0, 1000);

		for ($i = 0; $i < $count; $i++) {
			do {
				$popular_entity = $entities[array_rand($entities)];
			} while (array_key_exists($popular_entity->guid, $relationships));

			$relationships[$popular_entity->guid] = [];

			for ($c = 0; $c < $max; $c++) {
				do {
					$fan_entity = $entities[array_rand($entities)];
				} while ($fan_entity->guid == $popular_entity->guid || in_array($fan_entity->guid, $relationships[$popular_entity->guid]));

				$relationships[$popular_entity->guid][] = $fan_entity->guid;
				add_entity_relationship($fan_entity->guid, $relationship_name, $popular_entity->guid);
			}

			$max--;
		}

		$entities = elgg_get_entities_from_relationship_count([
			'relationship' => $relationship_name,
			'limit' => $count,
		]);

		foreach ($entities as $e) {
			$fan_entities = elgg_get_entities([
				'relationship' => $relationship_name,
				'limit' => 100,
				'relationship_guid' => $e->guid,
				'inverse_relationship' => true,
			]);

			$this->assertEquals(count($fan_entities), count($relationships[$e->guid]));

			foreach ($fan_entities as $fan_entity) {
				$this->assertTrue(in_array($fan_entity->guid, $relationships[$e->guid]));
				$this->assertNotFalse(check_entity_relationship($fan_entity->guid, $relationship_name, $e->guid));
			}
		}
	}

	/**
	 * Make sure elgg_get_entities() returns distinct (unique) results when relationship_guid is not
	 * set See #5775
	 */
	public function testElggApiGettersEntityRelationshipDistinctResult() {

		$relationship = 'test_5775_' . rand();
		$obj1 = new ElggObject();
		$obj1->setSubtype($this->getRandomSubtype());
		$obj1->save();

		$obj2 = new ElggObject();
		$obj2->setSubtype($this->getRandomSubtype());
		$obj2->save();

		$obj3 = new ElggObject();
		$obj3->setSubtype($this->getRandomSubtype());
		$obj3->save();

		add_entity_relationship($obj2->guid, $relationship, $obj1->guid);
		add_entity_relationship($obj3->guid, $relationship, $obj1->guid);

		$options = [
			'relationship' => $relationship,
			'inverse_relationship' => false,
			'count' => true,
		];

		$count = elgg_get_entities($options);
		$this->assertEquals(1, $count);

		unset($options['count']);
		$objects = elgg_get_entities($options);
		$this->assertIsArray($objects);
		$this->assertCount(1, $objects);

		$obj1->delete();
		$obj2->delete();
		$obj3->delete();
	}

	/**
	 * Make sure changes related to #5775 do not affect inverse relationship queries
	 */
	public function testElggApiGettersEntityRelationshipDistinctResultInverse() {

		$obj1 = new ElggObject();
		$obj1->setSubtype($this->getRandomSubtype());
		$obj1->save();

		$obj2 = new ElggObject();
		$obj2->setSubtype($this->getRandomSubtype());
		$obj2->save();

		$obj3 = new ElggObject();
		$obj3->setSubtype($this->getRandomSubtype());
		$obj3->save();

		add_entity_relationship($obj2->guid, 'test_5775_inverse', $obj1->guid);
		add_entity_relationship($obj3->guid, 'test_5775_inverse', $obj1->guid);

		$options = [
			'relationship' => 'test_5775_inverse',
			'inverse_relationship' => true,
			'count' => true,
		];

		$count = elgg_get_entities($options);
		$this->assertEquals(2, $count);

		unset($options['count']);
		$objects = elgg_get_entities($options);
		$this->assertIsArray($objects);
		$this->assertCount(2, $objects);

		$obj1->delete();
		$obj2->delete();
		$obj3->delete();
	}
	
	/**
	 * Check that you can get entities by filtering them on relationship time created lower
	 */
	public function testGetEntitiesFromRelationshipFilterByTimeCreatedLower() {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		// get a timestamp before creating the relationship
		$dt = _elgg_services()->relationshipsTable->getCurrentTime();
		$ts_lower = $dt->getTimestamp() - 1;
		
		add_entity_relationship($object1->guid, 'testGetEntitiesFromRelationship', $object2->guid);
		
		// get a timestamp after creating the relationship
		$ts_upper = $dt->getTimestamp() + 1;
		
		// check that if ts_lower is before the relationship you get the just created entity
		$es = elgg_get_entities([
			'relationship' => 'testGetEntitiesFromRelationship',
			'relationship_guid' => $object1->guid,
			'relationship_created_time_lower' => $ts_lower,
		]);
		$this->assertIsArray($es);
		$this->assertCount(1, $es);
		
		foreach ($es as $e) {
			$this->assertEquals($object2->guid, $e->guid);
		}
		
		// check that if ts_lower is after the relationship you get no entities
		$es = elgg_get_entities([
			'relationship' => 'testGetEntitiesFromRelationship',
			'relationship_guid' => $object1->guid,
			'relationship_created_time_lower' => $ts_upper,
		]);
		$this->assertIsArray($es);
		$this->assertCount(0, $es);
		
		$object1->delete();
		$object2->delete();
	}
	
	/**
	 * Check that you can get entities by filtering them on relationship time created upper
	 */
	public function testGetEntitiesFromRelationshipFilterByTimeCreatedUpper() {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		// get a timestamp before creating the relationship
		$dt = _elgg_services()->relationshipsTable->getCurrentTime();
		$ts_lower = $dt->getTimestamp() - 1;
		
		add_entity_relationship($object1->guid, 'testGetEntitiesFromRelationship', $object2->guid);
		
		// get a timestamp after creating the relationship
		$ts_upper = $dt->getTimestamp() + 1;
		
		// check that if ts_upper is after the relationship you get the just created entity
		$es = elgg_get_entities([
			'relationship' => 'testGetEntitiesFromRelationship',
			'relationship_guid' => $object1->guid,
			'relationship_created_time_upper' => $ts_upper,
		]);
		$this->assertIsArray($es);
		$this->assertCount(1, $es);
		
		foreach ($es as $e) {
			$this->assertEquals($object2->guid, $e->guid);
		}
		
		// check that if ts_upper is before the relationship you get no entities
		$es = elgg_get_entities([
			'relationship' => 'testGetEntitiesFromRelationship',
			'relationship_guid' => $object1->guid,
			'relationship_created_time_upper' => $ts_lower,
		]);
		$this->assertIsArray($es);
		$this->assertCount(0, $es);
		
		$object1->delete();
		$object2->delete();
	}
	
	/**
	 * Check that you can get entities by filtering them on relationship time created lower and upper
	 */
	public function testGetEntitiesFromRelationshipFilterByTimeCreatedLowerAndUpper() {
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		
		// get a timestamp before creating the relationship
		$dt = _elgg_services()->relationshipsTable->getCurrentTime();
		$ts_lower = $dt->getTimestamp() - 1;
		
		add_entity_relationship($object1->guid, 'testGetEntitiesFromRelationship', $object2->guid);
		
		// get a timestamp after creating the relationship
		$ts_upper = $dt->getTimestamp() + 1;
		
		// check that if relationship time created is between lower and upper you get the just created entity
		$es = elgg_get_entities([
			'relationship' => 'testGetEntitiesFromRelationship',
			'relationship_guid' => $object1->guid,
			'relationship_created_time_lower' => $ts_lower,
			'relationship_created_time_upper' => $ts_upper,
		]);
		$this->assertIsArray($es);
		$this->assertCount(1, $es);
		
		foreach ($es as $e) {
			$this->assertEquals($object2->guid, $e->guid);
		}
		
		// check that if  ts_lower > ts_upper you get no entities
		$es = elgg_get_entities([
			'relationship' => 'testGetEntitiesFromRelationship',
			'relationship_guid' => $object1->guid,
			'relationship_created_time_lower' => $ts_upper,
			'relationship_created_time_upper' => $ts_lower,
		]);
		$this->assertIsArray($es);
		$this->assertCount(0, $es);
		
		$object1->delete();
		$object2->delete();
	}
}
