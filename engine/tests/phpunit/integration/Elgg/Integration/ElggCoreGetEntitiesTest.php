<?php

namespace Elgg\Integration;

use Elgg\Database\QueryBuilder;

/**
 * Test elgg_get_entities()
 *
 * @group IntegrationTests
 * @group Entities
 * @group EntityTypeSubtypePairs
 */
class ElggCoreGetEntitiesTest extends ElggCoreGetEntitiesBaseTest {

	/***********************************
	 * TYPE TESTS
	 ***********************************
	 * check for getting a valid type in all ways we can.
	 * note that these aren't wonderful tests as there will be
	 * existing entities so we can't test against the ones we just created.
	 * So these just test that some are returned and match the type(s) requested.
	 * It could definitely be the case that the first 10 entities retrieved are all
	 * objects.  Maybe best to limit to 4 and group by type.
	 */
	public function testElggAPIGettersValidTypeUsingType() {
		$this->createOne();

		$es = elgg_get_entities([
			'type' => 'object',
			'group_by' => 'e.type',
		]);
		$this->assertIsArray($es);

		// should only ever return one object because of group by
		$this->assertCount(1, $es);
		foreach ($es as $e) {
			$this->assertEquals('object', $e->getType());
		}
	}

	public function testElggAPIGettersValidTypeUsingTypesAsString() {
		$this->createOne();

		$type = 'object';

		$es = elgg_get_entities([
			'types' => $type,
			'group_by' => 'e.type',
		]);
		$this->assertIsArray($es);

		// should only ever return one object because of group by
		$this->assertCount(1, $es);
		foreach ($es as $e) {
			$this->assertEquals('object', $e->getType());
		}
	}

	public function testElggAPIGettersValidTypeUsingTypesAsArray() {
		$valid_types = ['object'];
		$this->createMany($valid_types, 1);
		
		$es = elgg_get_entities([
			'types' => $valid_types,
			'group_by' => 'e.type',
		]);
		$this->assertIsArray($es);

		// should only ever return one object because of group by
		$this->assertCount(1, $es);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $valid_types));
		}
	}

	public function testElggAPIGettersValidTypeUsingTypesAsArrayPlural() {
		$valid_types = ['object', 'user'];
		$this->createMany($valid_types, 1);
		
		$es = elgg_get_entities([
			'types' => $valid_types,
			'group_by' => 'e.type',
		]);
		$this->assertIsArray($es);

		// one of object and one of group
		$this->assertCount(count($valid_types), $es);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $valid_types));
		}
	}

	/**
	 * Test mixed valid and invalid types.
	 */

	public function testElggAPIGettersValidAndInvalidTypes() {
		_elgg_services()->logger->disable();
		
		$this->createOne();
		$valid = 'object';

		$invalid = 'invalid_object';
		$es = elgg_get_entities([
			'types' => [
				$invalid,
				$valid,
			],
			'group_by' => 'e.type',
		]);
		$this->assertIsArray($es);

		// should only ever return one object because of group by
		$this->assertCount(1, $es);
		$this->assertEquals($valid, $es[0]->getType());
		
		_elgg_services()->logger->enable();
	}

	public function testElggAPIGettersValidAndInvalidTypesPlural() {
		_elgg_services()->logger->disable();
		
		$valid = ['object', 'user'];
		$invalid = ['invalid_object', 'invalid_user', 'invalid_group'];

		$types = [];
		foreach ($valid as $t) {
			$types[] = $t;
		}

		foreach ($invalid as $t) {
			$types[] = $t;
		}

		shuffle($types);
		
		$es = elgg_get_entities([
			'types' => $types,
			'group_by' => 'e.type'
		]);
		$this->assertIsArray($es);

		// should only ever return one object because of group by
		$this->assertCount(count($valid), $es);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $valid));
		}
		
		_elgg_services()->logger->enable();
	}

	/**************************************
	 * SUBTYPE TESTS
	 **************************************
	 *
	 * Here we can use the subtypes we created to test more finely.
	 * Subtypes are bound to types, so we must pass a type.
	 * This is where the fun logic starts.
	 */

	public function testElggAPIGettersValidSubtypeUsingSubtypeSingularType() {
		$entity = $this->createOne();
		
		$es = elgg_get_entities( [
			'types' => $entity->type,
			'subtype' => $entity->getSubtype(),
		]);
		$this->assertIsArray($es);
		$this->assertCount(1, $es);
		
		foreach ($es as $e) {
			$this->assertEquals($entity->getType(), $e->getType());
			$this->assertEquals($entity->getSubtype(), $e->getSubtype());
		}
	}

	public function testElggAPIGettersValidSubtypeUsingSubtypesAsStringSingularType() {
		$entity = $this->createOne();
		
		$es = elgg_get_entities([
			'types' => $entity->type,
			'subtypes' => $entity->getSubtype(),
		]);
		$this->assertIsArray($es);
		$this->assertCount(1, $es);
		
		foreach ($es as $e) {
			$this->assertEquals($entity->getType(), $e->getType());
			$this->assertEquals($entity->getSubtype(), $e->getSubtype());
		}
	}

	public function testElggAPIGettersValidSubtypeUsingSubtypesAsArraySingularType() {
		$entity = $this->createOne();
		
		$es = elgg_get_entities([
			'types' => $entity->type,
			'subtypes' => [$entity->getSubtype()],
		]);
		$this->assertIsArray($es);
		$this->assertCount(1, $es);
		
		foreach ($es as $e) {
			$this->assertEquals($entity->getType(), $e->getType());
			$this->assertEquals($entity->getSubtype(), $e->getSubtype());
		}
	}

	public function testElggAPIGettersValidSubtypeUsingPluralSubtypesSingularType() {
		$subtype_num = 2;
		$types = ['object'];
		$entities = $this->createMany($types, $subtype_num);
		$subtypes = array_map(function($e) {
			return $e->getSubtype();
		}, $entities);

		$es = elgg_get_entities([
			'types' => $types,
			'subtypes' => $subtypes,
		]);
		$this->assertIsArray($es);
		$this->assertCount($subtype_num, $es);
		
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $types));
			$this->assertTrue(in_array($e->getSubtype(), $subtypes));
		}
	}

	/**
	* Because we're looking for type OR subtype (sorta)
	* it's possible that we've pulled in entities that aren't
	* of the subtype we've requested.
	* THIS COMBINATION MAKES LITTLE SENSE.
	* There is no mechanism in elgg to retrieve a subtype without a type, so
	* this combo gets trimmed down to only including subtypes that are valid to
	* each particular type.
	* FOR THE LOVE OF ALL GOOD PLEASE JUST USE TYPE_SUBTYPE_PAIRS!
	 */
	public function testElggAPIGettersValidSubtypeUsingPluralSubtypesPluralTypes() {
		$subtype_num = 2;
		$types = ['object', 'group'];
		$type_num = count($types);
		$entities = $this->createMany($types, $subtype_num);
		$subtypes = array_map(function($e) {
			return $e->getSubtype();
		}, $entities);

		$es = elgg_get_entities([
			'types' => $types,
			'subtypes' => $subtypes,
		]);
		$this->assertIsArray($es);
		// this will unset all invalid subtypes for each type that that only
		// one entity exists of each.
		$this->assertCount($subtype_num * $type_num, $es);
		
		foreach ($es as $e) {
			// entities must at least be in the type.
			$this->assertTrue(in_array($e->getType(), $types));

			// test that this is a valid subtype for the entity type.
			$this->assertTrue(in_array($e->getSubtype(), $subtypes));
		}
	}

	/**
	 * This combination will remove all invalid subtypes for this type.
	 */
	public function testElggAPIGettersValidSubtypeUsingPluralMixedSubtypesSingleType() {
		$types = ['object'];
		$type_num = count($types);
		$entities = $this->createMany($types, 1);
		$valid_subtypes = array_map(function($e) {
			return $e->getSubtype();
		}, $entities);
		$subtypes = $valid_subtypes;
		$subtypes[] = $this->getRandomSubtype();

		$es = elgg_get_entities([
			'types' => $types,
			'subtypes' => $subtypes,
		]);
		$this->assertIsArray($es);
		// this will unset all invalid subtypes for each type that that only
		// one entity exists of each.
		$this->assertCount($type_num, $es);
		
		foreach ($es as $e) {
			// entities must at least be in the type.
			$this->assertTrue(in_array($e->getType(), $types));

			// test that this is a valid subtype for the entity type.
			$this->assertTrue(in_array($e->getSubtype(), $valid_subtypes));
		}
	}

	/***************************
	 * TYPE_SUBTYPE_PAIRS
	 ***************************/

	/**
	 * Valid type, valid subtype pairs
	 */
	public function testElggAPIGettersTSPValidTypeValidSubtype() {
		$subtype_num = 1;
		$types = ['object'];
		$type_num = count($types);
		$entities = $this->createMany($types, $subtype_num);
		$subtypes = array_map(function($e) {
			return $e->getSubtype();
		}, $entities);

		$es = elgg_get_entities([
			'type_subtype_pairs' => [
				$types[0] => $subtypes[0],
			],
		]);
		$this->assertIsArray($es);
		$this->assertCount($type_num, $es);
		
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $types));
			$this->assertTrue(in_array($e->getSubtype(), $subtypes));
		}
	}

	/**
	 * Valid type, multiple valid subtypes
	 */
	public function testElggAPIGettersTSPValidTypeValidPluralSubtype() {
		$subtype_num = 3;
		$types = ['object'];
		$entities = $this->createMany($types, $subtype_num);
		$subtypes = array_map(function($e) {
			return $e->getSubtype();
		}, $entities);

		$es = elgg_get_entities([
			'type_subtype_pairs' => [
				$types[0] => $subtypes,
			],
		]);
		$this->assertIsArray($es);
		$this->assertCount($subtype_num, $es);
		
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $types));
			$this->assertTrue(in_array($e->getSubtype(), $subtypes));
		}
	}

	/**
	 * Valid type, both valid and invalid subtypes
	 */
	public function testElggAPIGettersTSPValidTypeMixedPluralSubtype() {
		$valid_subtype_num = 2;
		$types = ['object'];
		$entities = $this->createMany($types, $valid_subtype_num);
		$valid = array_map(function($e) {
			return $e->getSubtype();
		}, $entities);
		$invalid = [$this->getRandomSubtype()];

		$subtypes = array_merge($valid, $invalid);
		shuffle($subtypes);

		$es = elgg_get_entities([
			'type_subtype_pairs' => [
				$types[0] => $subtypes,
			],
		]);
		$this->assertIsArray($es);
		$this->assertCount($valid_subtype_num, $es);
		
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $types));
			$this->assertTrue(in_array($e->getSubtype(), $valid));
		}
	}


	/****************************
	 * empty-RETURNING TESTS
	 ****************************
	 * The original bug returned
	 * all entities when invalid subtypes were passed.
	 * Because there's a huge numer of combinations that
	 * return entities, I'm only writing tests for
	 * things that should return an empty array.
	 *
	 * I'm leaving the above in case anyone is inspired to
	 * write out the rest of the possible combinations
	 */

	/**
	 * Test invalid types with singular 'type'.
	 */
	public function testElggApiGettersInvalidTypeUsingType() {
		_elgg_services()->logger->disable();

		$es = elgg_get_entities([
			'type' => 'some_invalid_type',
		]);
		$this->assertEmpty($es);

		_elgg_services()->logger->enable();
	}

	/**
	 * Test invalid types with plural 'types'.
	 */
	public function testElggApiGettersInvalidTypeUsingTypesAsString() {
		_elgg_services()->logger->disable();

		$es = elgg_get_entities([
			'types' => 'some_invalid_type',
		]);
		$this->assertEmpty($es);

		_elgg_services()->logger->enable();
	}

	/**
	 * Test invalid types with plural 'types' and an array of a single type
	 */
	public function testElggApiGettersInvalidTypeUsingTypesAsArray() {
		_elgg_services()->logger->disable();

		$es = elgg_get_entities([
			'types' => ['some_invalid_type'],
		]);
		$this->assertEmpty($es);

		_elgg_services()->logger->enable();
	}

	/**
	 * Test invalid types with plural 'types' and an array of a two types
	 */
	public function testElggApiGettersInvalidTypes() {
		_elgg_services()->logger->disable();

		$es = elgg_get_entities([
			'types' => [
				'invalid_type1', 'invalid_type2',
			],
		]);
		$this->assertEmpty($es);

		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersInvalidSubtypeValidType() {
		_elgg_services()->logger->disable();

		$es = elgg_get_entities([
			'types' => [
				'invalid_type1',
			],
			'subtypes' => [
				$this->getRandomSubtype(),
			],
		]);
		$this->assertEmpty($es);

		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersInvalidSubtypeValidTypes() {
		_elgg_services()->logger->disable();

		$es = elgg_get_entities([
			'types' => [
				'invalid_type1',
				'invalid_type2',
			],
			'subtypes' => [
				$this->getRandomSubtype(),
			],
		]);
		$this->assertEmpty($es);

		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersInvalidSubtypesValidType() {
		_elgg_services()->logger->disable();

		$es = elgg_get_entities([
			'types' => [
				'invalid_type1',
			],
			'subtypes' => [
				$this->getRandomSubtype(),
				$this->getRandomSubtype(),
			],
		]);
		$this->assertEmpty($es);

		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersInvalidSubtypesValidTypes() {
		_elgg_services()->logger->disable();

		$es = elgg_get_entities([
			'types' => [
				'invalid_type1',
				'invalid_type2',
			],
			'subtypes' => [
				$this->getRandomSubtype(),
				$this->getRandomSubtype(),
			],
		]);
		$this->assertEmpty($es);

		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersTSPInvalidType() {
		_elgg_services()->logger->disable();
		
		$es = elgg_get_entities([
			'type_subtype_pairs' => [
				'invalid_type1' => null,
			],
		]);
		$this->assertEmpty($es);
		
		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersTSPInvalidTypes() {
		_elgg_services()->logger->disable();

		$es = elgg_get_entities([
			'type_subtype_pairs' => [
				'invalid_type1' => null,
				'invalid_type2' => null,
			],
		]);
		$this->assertEmpty($es);
		
		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersTSPValidTypeInvalidSubtype() {
		_elgg_services()->logger->disable();

		$es = elgg_get_entities([
			'type_subtype_pairs' => [
				'invlaid_type1' => $this->getRandomSubtype(),
			],
		]);
		$this->assertEmpty($es);
		
		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersTSPValidTypeInvalidSubtypes() {
		_elgg_services()->logger->disable();

		$es = elgg_get_entities([
			'type_subtype_pairs' => [
				'invlaid_type1' => [
					$this->getRandomSubtype(),
					$this->getRandomSubtype(),
				],
			],
		]);
		$this->assertEmpty($es);
		
		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersTSPValidTypesInvalidSubtypes() {
		_elgg_services()->logger->disable();
		
		$es = elgg_get_entities([
			'type_subtype_pairs' => [
				'invlaid_type1' => [
					$this->getRandomSubtype(),
					$this->getRandomSubtype(),
				],
				'invalid_type2' => [
					$this->getRandomSubtype(),
					$this->getRandomSubtype(),
				],
			],
		]);
		$this->assertEmpty($es);
		
		_elgg_services()->logger->enable();
	}

	public function testElggGetEntitiesByGuidSingular() {
		$entities = $this->createMany(['object', 'group', 'user'], 1);
		foreach ($entities as $e) {
			$es = elgg_get_entities([
				'guid' => $e->guid,
			]);

			$this->assertCount(1, $es);
			$this->assertEquals($e->guid, $es[0]->guid);
		}
	}

	public function testElggGetEntitiesByGuidPlural() {
		$guids = [];

		$entities = $this->createMany(['object', 'group', 'user'], 1);

		foreach ($entities as $e) {
			$guids[] = $e->guid;
		}

		$es = elgg_get_entities([
			'guids' => $guids,
			'limit' => 100,
		]);

		$this->assertEquals(count($es), count($entities));

		foreach ($es as $e) {
			$this->assertTrue(in_array($e->guid, $guids));
		}
	}

	public function testElggGetEntitiesBadWheres() {
		$entities = elgg_get_entities([
			'container_guid' => 'abc',
		]);
		$this->assertEmpty($entities);
	}

	public function testEGEEmptySubtypePlurality() {
		$entities = elgg_get_entities([
			'type' => 'user',
			'subtypes' => '',
		]);
		$this->assertIsArray($entities);

		$entities = elgg_get_entities([
			'type' => 'user',
			'subtype' => '',
		]);
		$this->assertIsArray($entities);

		$entities = elgg_get_entities([
			'type' => 'user',
			'subtype' => [''],
		]);
		$this->assertIsArray($entities);

		$entities = elgg_get_entities([
			'type' => 'user',
			'subtypes' => [''],
		]);
		$this->assertIsArray($entities);
	}

	public function testDistinctCanBeDisabled() {
		$options = [
			'callback' => '',
			'wheres' => [
				function (QueryBuilder $qb, $main_alias) {
					$md = $qb->joinMetadataTable($main_alias, 'guid', null, 'right');
					
					return $qb->compare("{$md}.entity_guid", '=', elgg_get_logged_in_user_guid(), ELGG_VALUE_GUID);
				},
			],
		];

		$users = elgg_get_entities($options);
		$this->assertCount(1, $users);

		$options['distinct'] = false;
		$users = elgg_get_entities($options);
		$this->assertGreaterThan(1, $users);
	}
	
	public function testElggGetEntitiesWithoutPrivateSettingsPreloader() {
		$entities = $this->createMany('object', 2);
		
		$guids = [];
		foreach ($entities as $e) {
			$guids[] = $e->guid;
			$e->setPrivateSetting('foo', 'bar');
		}

		elgg_get_entities([
			'guids' => $guids,
			'limit' => false,
		]);
				
		$cache = _elgg_services()->privateSettingsCache;
		
		// cache should not be loaded
		$this->assertNull($cache->load($guids[0]));
		$this->assertNull($cache->load($guids[1]));
		
		// cleanup
		foreach ($entities as $e) {
			$e->delete();
		}
	}
	
	public function testElggGetEntitiesWithPrivateSettingsPreloader() {
		$entities = $this->createMany('object', 2);
		
		$guids = [];
		foreach ($entities as $e) {
			$guids[] = $e->guid;
			$e->setPrivateSetting('foo', 'bar');
		}

		elgg_get_entities([
			'guids' => $guids,
			'limit' => false,
			'preload_private_settings' => true,
		]);
				
		$cache = _elgg_services()->privateSettingsCache;
		
		// cache should be loaded
		$this->assertSame(['foo' => 'bar'], $cache->load($guids[0]));
		$this->assertSame(['foo' => 'bar'], $cache->load($guids[1]));
		
		// cleanup
		foreach ($entities as $e) {
			$e->delete();
		}
	}
}
