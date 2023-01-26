<?php

namespace Elgg\Integration;

/**
 * Test elgg_get_entities() with relationship options and
 * elgg_get_entities_from_relationship_count()
 *
 * @group IntegrationTests
 * @group Entities
 * @group EntityRelationships
 */
class ElggCoreGetEntitiesFromRelationshipTest extends ElggCoreGetEntitiesIntegrationTestCase {

	// Make sure metadata doesn't affect getting entities by relationship.  See #2274
	public function testElggApiGettersEntityRelationshipWithMetadata() {
		$obj1 = $this->createObject();
		$obj1->test_md = 'test';
		
		$obj2 = $this->createObject();
		$obj2->test_md = 'test';
		
		$this->assertTrue($obj1->addRelationship($obj2->guid, 'test'));

		$es = elgg_get_entities([
			'relationship' => 'test',
			'relationship_guid' => $obj1->guid,
		]);
		$this->assertIsArray($es);
		$this->assertCount(1, $es);

		foreach ($es as $e) {
			$this->assertEquals($e->guid, $obj2->guid);
		}
	}

	public function testElggApiGettersEntityRelationshipWithOutMetadata() {
		$obj1 = $this->createObject();
		$obj2 = $this->createObject();

		$this->assertTrue($obj1->addRelationship($obj2->guid, 'test'));

		$es = elgg_get_entities([
			'relationship' => 'test',
			'relationship_guid' => $obj1->guid,
		]);
		$this->assertIsArray($es);
		$this->assertCount(1, $es);

		foreach ($es as $e) {
			$this->assertEquals($e->guid, $obj2->guid);
		}
	}

	public function testElggApiGettersEntityRelationshipWithMetadataIncludingRealMetadata() {
		$obj1 = $this->createObject();
		$obj1->test_md = 'test';
		
		$obj2 = $this->createObject();
		$obj2->test_md = 'test';
		
		$this->assertTrue($obj1->addRelationship($obj2->guid, 'test'));

		$es = elgg_get_entities([
			'relationship' => 'test',
			'relationship_guid' => $obj1->guid,
			'metadata_name' => 'test_md',
			'metadata_value' => 'test',
		]);
		$this->assertIsArray($es);
		$this->assertCount(1, $es);

		foreach ($es as $e) {
			$this->assertEquals($e->guid, $obj2->guid);
		}
	}

	public function testElggApiGettersEntityRelationshipWithMetadataIncludingFakeMetadata() {
		$obj1 = $this->createObject();
		$obj1->test_md = 'test';
		
		$obj2 = $this->createObject();
		$obj2->test_md = 'test';
		
		$this->assertTrue($obj1->addRelationship($obj2->guid, 'test'));

		$es = elgg_get_entities([
			'relationship' => 'test',
			'relationship_guid' => $obj1->guid,
			'metadata_name' => 'test_md',
			'metadata_value' => 'invalid',
		]);

		$this->assertEmpty($es);
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
				$fan_entity->addRelationship($popular_entity->guid, $relationship_name);
			}

			$max--;
		}

		$entities = elgg_get_entities_from_relationship_count([
			'relationship' => $relationship_name,
			'limit' => $count,
		]);

		foreach ($entities as $e) {
			$fan_entities = elgg_get_entities([
				'limit' => 100,
				'relationship' => $relationship_name,
				'relationship_guid' => $e->guid,
				'inverse_relationship' => true,
			]);

			$this->assertCount(count($relationships[$e->guid]), $fan_entities);

			foreach ($fan_entities as $fan_entity) {
				$this->assertTrue(in_array($fan_entity->guid, $relationships[$e->guid]));
				$this->assertTrue($fan_entity->hasRelationship($e->guid, $relationship_name));
			}
		}
	}

	/**
	 * Make sure elgg_get_entities() returns distinct (unique) results when relationship_guid is not
	 * set See #5775
	 */
	public function testElggApiGettersEntityRelationshipDistinctResult() {
		$relationship = 'test_5775_' . rand();
		
		$obj1 = $this->createObject();
		$obj2 = $this->createObject();
		$obj3 = $this->createObject();

		$this->assertTrue($obj2->addRelationship($obj1->guid, $relationship));
		$this->assertTrue($obj3->addRelationship($obj1->guid, $relationship));
		
		$objects = elgg_get_entities([
			'relationship' => $relationship,
			'inverse_relationship' => false,
		]);
		$this->assertCount(1, $objects);
	}

	/**
	 * Make sure changes related to #5775 do not affect inverse relationship queries
	 */
	public function testElggApiGettersEntityRelationshipDistinctResultInverse() {
		$relationship = 'test_5775_inverse_' . rand();

		$obj1 = $this->createObject();
		$obj2 = $this->createObject();
		$obj3 = $this->createObject();

		$this->assertTrue($obj2->addRelationship($obj1->guid, $relationship));
		$this->assertTrue($obj3->addRelationship($obj1->guid, $relationship));

		$objects = elgg_get_entities([
			'relationship' => $relationship,
			'inverse_relationship' => true,
		]);
		$this->assertCount(2, $objects);
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
		
		$this->assertTrue($object1->addRelationship($object2->guid, 'testGetEntitiesFromRelationship'));
		
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
		
		$this->assertTrue($object1->addRelationship($object2->guid, 'testGetEntitiesFromRelationship'));
		
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
		
		$this->assertTrue($object1->addRelationship($object2->guid, 'testGetEntitiesFromRelationship'));
		
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
	}
}
