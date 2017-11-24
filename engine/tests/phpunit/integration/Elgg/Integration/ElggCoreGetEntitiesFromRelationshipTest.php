<?php

namespace Elgg\Integration;

use ElggObject;

/**
 * Test elgg_get_entities_from_relationship() and
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
		$obj1->subtype = $this->getRandomSubtype();
		$obj1->test_md = 'test';
		$obj1->save();
		$guids[] = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->subtype = $this->getRandomSubtype();
		$obj2->test_md = 'test';
		$obj2->save();
		$guids[] = $obj2->guid;

		add_entity_relationship($guids[0], 'test', $guids[1]);

		$options = [
			'relationship' => 'test',
			'relationship_guid' => $guids[0]
		];

		$es = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($es));
		$this->assertIdentical(count($es), 1);

		foreach ($es as $e) {
			$this->assertEqual($guids[1], $e->guid);
		}

		foreach ($guids as $guid) {
			$e = get_entity($guid);
			$e->delete();
		}
	}

	public function testElggApiGettersEntityRelationshipWithOutMetadata() {
		$guids = [];

		$obj1 = new ElggObject();
		$obj1->subtype = $this->getRandomSubtype();
		$obj1->save();
		$guids[] = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->subtype = $this->getRandomSubtype();
		$obj2->save();
		$guids[] = $obj2->guid;

		add_entity_relationship($guids[0], 'test', $guids[1]);

		$options = [
			'relationship' => 'test',
			'relationship_guid' => $guids[0]
		];

		$es = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($es));
		$this->assertIdentical(count($es), 1);

		foreach ($es as $e) {
			$this->assertEqual($guids[1], $e->guid);
		}

		foreach ($guids as $guid) {
			$e = get_entity($guid);
			$e->delete();
		}
	}

	public function testElggApiGettersEntityRelationshipWithMetadataIncludingRealMetadata() {
		$guids = [];

		$obj1 = new ElggObject();
		$obj1->subtype = $this->getRandomSubtype();
		$obj1->test_md = 'test';
		$obj1->save();
		$guids[] = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->subtype = $this->getRandomSubtype();
		$obj2->test_md = 'test';
		$obj2->save();
		$guids[] = $obj2->guid;

		add_entity_relationship($guids[0], 'test', $guids[1]);

		$options = [
			'relationship' => 'test',
			'relationship_guid' => $guids[0],
			'metadata_name' => 'test_md',
			'metadata_value' => 'test',
		];

		$es = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($es));
		$this->assertIdentical(count($es), 1);

		foreach ($es as $e) {
			$this->assertEqual($guids[1], $e->guid);
		}

		foreach ($guids as $guid) {
			$e = get_entity($guid);
			$e->delete();
		}
	}

	public function testElggApiGettersEntityRelationshipWithMetadataIncludingFakeMetadata() {
		$guids = [];

		$obj1 = new ElggObject();
		$obj1->subtype = $this->getRandomSubtype();
		$obj1->test_md = 'test';
		$obj1->save();
		$guids[] = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->subtype = $this->getRandomSubtype();
		$obj2->test_md = 'test';
		$obj2->save();
		$guids[] = $obj2->guid;

		add_entity_relationship($guids[0], 'test', $guids[1]);

		$options = [
			'relationship' => 'test',
			'relationship_guid' => $guids[0],
			'metadata_name' => 'test_md',
			'metadata_value' => 'invalid',
		];

		$es = elgg_get_entities_from_relationship($options);

		$this->assertTrue(empty($es));

		foreach ($guids as $guid) {
			$e = get_entity($guid);
			$e->delete();
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

		$options = [
			'relationship' => $relationship_name,
			'limit' => $count
		];

		$entities = elgg_get_entities_from_relationship_count($options);

		foreach ($entities as $e) {
			$options = [
				'relationship' => $relationship_name,
				'limit' => 100,
				'relationship_guid' => $e->guid,
				'inverse_relationship' => true
			];

			$fan_entities = elgg_get_entities_from_relationship($options);

			$this->assertEqual(count($fan_entities), count($relationships[$e->guid]));

			foreach ($fan_entities as $fan_entity) {
				$this->assertTrue(in_array($fan_entity->guid, $relationships[$e->guid]));
				$this->assertNotIdentical(false, check_entity_relationship($fan_entity->guid, $relationship_name, $e->guid));
			}
		}
	}

	/**
	 * Make sure elgg_get_entities_from_relationship() returns distinct (unique) results when relationship_guid is not
	 * set See #5775
	 */
	public function testElggApiGettersEntityRelationshipDistinctResult() {

		$relationship = 'test_5775_' . rand();
		$obj1 = new ElggObject();
		$obj1->subtype = $this->getRandomSubtype();
		$obj1->save();

		$obj2 = new ElggObject();
		$obj2->subtype = $this->getRandomSubtype();
		$obj2->save();

		$obj3 = new ElggObject();
		$obj3->subtype = $this->getRandomSubtype();
		$obj3->save();

		add_entity_relationship($obj2->guid, $relationship, $obj1->guid);
		add_entity_relationship($obj3->guid, $relationship, $obj1->guid);

		$options = [
			'relationship' => $relationship,
			'inverse_relationship' => false,
			'count' => true,
		];

		$count = elgg_get_entities_from_relationship($options);
		$this->assertIdentical($count, 1);

		unset($options['count']);
		$objects = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($objects));
		$this->assertIdentical(count($objects), 1);

		$obj1->delete();
		$obj2->delete();
		$obj3->delete();
	}

	/**
	 * Make sure changes related to #5775 do not affect inverse relationship queries
	 */
	public function testElggApiGettersEntityRelationshipDistinctResultInverse() {


		$obj1 = new ElggObject();
		$obj1->subtype = $this->getRandomSubtype();
		$obj1->save();

		$obj2 = new ElggObject();
		$obj2->subtype = $this->getRandomSubtype();
		$obj2->save();

		$obj3 = new ElggObject();
		$obj3->subtype = $this->getRandomSubtype();
		$obj3->save();

		add_entity_relationship($obj2->guid, 'test_5775_inverse', $obj1->guid);
		add_entity_relationship($obj3->guid, 'test_5775_inverse', $obj1->guid);

		$options = [
			'relationship' => 'test_5775_inverse',
			'inverse_relationship' => true,
			'count' => true,
		];

		$count = elgg_get_entities_from_relationship($options);
		$this->assertIdentical($count, 2);

		unset($options['count']);
		$objects = elgg_get_entities_from_relationship($options);
		$this->assertTrue(is_array($objects));
		$this->assertIdentical(count($objects), 2);

		$obj1->delete();
		$obj2->delete();
		$obj3->delete();
	}

}
