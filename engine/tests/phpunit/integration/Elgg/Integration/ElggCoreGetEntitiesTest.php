<?php

namespace Elgg\Integration;

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

		$options = [
			'type' => 'object',
			'group_by' => 'e.type'
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		// should only ever return one object because of group by
		$this->assertIdentical(count($es), 1);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), ['object']));
		}
	}

	public function testElggAPIGettersValidTypeUsingTypesAsString() {
		$this->createOne();

		$type = 'object';

		$options = [
			'types' => $type,
			'group_by' => 'e.type'
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		// should only ever return one object because of group by
		$this->assertIdentical(count($es), 1);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), ['object']));
		}
	}

	public function testElggAPIGettersValidTypeUsingTypesAsArray() {
		$valid_types = ['object'];
		$this->createMany($valid_types, 1);
		$options = [
			'types' => $valid_types,
			'group_by' => 'e.type'
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		// should only ever return one object because of group by
		$this->assertIdentical(count($es), 1);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $valid_types));
		}
	}

	public function testElggAPIGettersValidTypeUsingTypesAsArrayPlural() {
		$valid_types = ['object', 'user'];
		$this->createMany($valid_types, 1);
		$options = [
			'types' => $valid_types,
			'group_by' => 'e.type'
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		// one of object and one of group
		$this->assertIdentical(count($es), count($valid_types));

		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $valid_types));
		}
	}


	/*
	 * Test mixed valid and invalid types.
	 */


	public function testElggAPIGettersValidAndInvalidTypes() {

		$this->createOne();
		$valid = 'object';

		$invalid = 'invalid_object';
		$options = [
			'types' => [
				$invalid,
				$valid
			],
			'group_by' => 'e.type'
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		// should only ever return one object because of group by
		$this->assertIdentical(count($es), 1);
		$this->assertIdentical($es[0]->getType(), $valid);
	}

	public function testElggAPIGettersValidAndInvalidTypesPlural() {

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
		$options = [
			'types' => $types,
			'group_by' => 'e.type'
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		// should only ever return one object because of group by
		$this->assertIdentical(count($es), count($valid));
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $valid));
		}
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
		$options = [
			'types' => $entity->type,
			'subtype' => $entity->getSubtype(),
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		$this->assertIdentical(count($es), 1);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), [$entity->type]));
			$this->assertTrue(in_array($e->getSubtype(), [$entity->getSubtype()]));
		}
	}

	public function testElggAPIGettersValidSubtypeUsingSubtypesAsStringSingularType() {
		$entity = $this->createOne();
		$options = [
			'types' => $entity->type,
			'subtype' => $entity->getSubtype(),
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		$this->assertIdentical(count($es), 1);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), [$entity->type]));
			$this->assertTrue(in_array($e->getSubtype(), [$entity->getSubtype()]));
		}
	}

	public function testElggAPIGettersValidSubtypeUsingSubtypesAsArraySingularType() {
		$entity = $this->createOne();
		$options = [
			'types' => $entity->type,
			'subtype' => [$entity->getSubtype()],
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		$this->assertIdentical(count($es), 1);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), [$entity->type]));
			$this->assertTrue(in_array($e->getSubtype(), [$entity->getSubtype()]));
		}
	}

	public function testElggAPIGettersValidSubtypeUsingPluralSubtypesSingularType() {
		$subtype_num = 2;
		$types = ['object'];
		$type_num = count($types);
		$entities = $this->createMany($types, $subtype_num);
		$subtypes = array_map(function($e) {
			return $e->getSubtype();
		}, $entities);

		$options = [
			'types' => $types,
			'subtypes' => $subtypes
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		$this->assertIdentical(count($es), $subtype_num);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $types));
			$this->assertTrue(in_array($e->getSubtype(), $subtypes));
		}
	}


	/*
	Because we're looking for type OR subtype (sorta)
	it's possible that we've pulled in entities that aren't
	of the subtype we've requested.
	THIS COMBINATION MAKES LITTLE SENSE.
	There is no mechanism in elgg to retrieve a subtype without a type, so
	this combo gets trimmed down to only including subtypes that are valid to
	each particular type.
	FOR THE LOVE OF ALL GOOD PLEASE JUST USE TYPE_SUBTYPE_PAIRS!
	 */
	public function testElggAPIGettersValidSubtypeUsingPluralSubtypesPluralTypes() {
		$subtype_num = 2;
		$types = ['object', 'group'];
		$type_num = count($types);
		$entities = $this->createMany($types, $subtype_num);
		$subtypes = array_map(function($e) {
			return $e->getSubtype();
		}, $entities);

		$options = [
			'types' => $types,
			'subtypes' => $subtypes,
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		// this will unset all invalid subtypes for each type that that only
		// one entity exists of each.
		$this->assertIdentical(count($es), $subtype_num * $type_num);
		foreach ($es as $e) {
			// entities must at least be in the type.
			$this->assertTrue(in_array($e->getType(), $types));

			// test that this is a valid subtype for the entity type.
			$this->assertTrue(in_array($e->getSubtype(), $subtypes));
		}
	}

	/*
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

		$options = [
			'types' => $types,
			'subtypes' => $subtypes
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		// this will unset all invalid subtypes for each type that that only
		// one entity exists of each.
		$this->assertIdentical(count($es), $type_num);
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

		$pair = [$types[0] => $subtypes[0]];

		$options = [
			'type_subtype_pairs' => $pair
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		$this->assertIdentical(count($es), $type_num);
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
		$type_num = count($types);
		$entities = $this->createMany($types, $subtype_num);
		$subtypes = array_map(function($e) {
			return $e->getSubtype();
		}, $entities);

		$pair = [$types[0] => $subtypes];

		$options = [
			'type_subtype_pairs' => $pair
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		$this->assertIdentical(count($es), $subtype_num);
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
		$type_num = count($types);
		$entities = $this->createMany($types, $valid_subtype_num);
		$valid = array_map(function($e) {
			return $e->getSubtype();
		}, $entities);
		$invalid = [$this->getRandomSubtype()];

		$subtypes = array_merge($valid, $invalid);
		shuffle($subtypes);

		$pair = [$types[0] => $subtypes];

		$options = [
			'type_subtype_pairs' => $pair
		];

		$es = elgg_get_entities($options);
		$this->assertInternalType('array', $es);

		$this->assertIdentical(count($es), $valid_subtype_num);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $types));
			$this->assertTrue(in_array($e->getSubtype(), $valid));
		}
	}


	/****************************
	 * false-RETURNING TESTS
	 ****************************
	 * The original bug returned
	 * all entities when invalid subtypes were passed.
	 * Because there's a huge numer of combinations that
	 * return entities, I'm only writing tests for
	 * things that should return false.
	 *
	 * I'm leaving the above in case anyone is inspired to
	 * write out the rest of the possible combinations
	 */


	/**
	 * Test invalid types with singular 'type'.
	 */
	public function testElggApiGettersInvalidTypeUsingType() {
		_elgg_services()->logger->disable();

		$options = [
			'type' => 'some_invalid_type',
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);

		_elgg_services()->logger->enable();
	}

	/**
	 * Test invalid types with plural 'types'.
	 */
	public function testElggApiGettersInvalidTypeUsingTypesAsString() {
		_elgg_services()->logger->disable();

		$options = [
			'types' => 'some_invalid_type',
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);

		_elgg_services()->logger->enable();
	}

	/**
	 * Test invalid types with plural 'types' and an array of a single type
	 */
	public function testElggApiGettersInvalidTypeUsingTypesAsArray() {
		_elgg_services()->logger->disable();

		$options = [
			'types' => ['some_invalid_type'],
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);

		_elgg_services()->logger->enable();
	}

	/**
	 * Test invalid types with plural 'types' and an array of a two types
	 */
	public function testElggApiGettersInvalidTypes() {
		_elgg_services()->logger->disable();

		$options = [
			'types' => ['invalid_type1', 'invalid_type2'],
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);

		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersInvalidSubtypeValidType() {
		_elgg_services()->logger->disable();

		$options = [
			'types' => ['invalid_type1'],
			'subtypes' => [$this->getRandomSubtype()],
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);

		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersInvalidSubtypeValidTypes() {
		_elgg_services()->logger->disable();

		$options = [
			'types' => ['invalid_type1', 'invalid_type2'],
			'subtypes' => [$this->getRandomSubtype()],
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);

		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersInvalidSubtypesValidType() {
		_elgg_services()->logger->disable();

		$options = [
			'types' => ['invalid_type1'],
			'subtypes' => [$this->getRandomSubtype(), $this->getRandomSubtype()],
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);

		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersInvalidSubtypesValidTypes() {
		_elgg_services()->logger->disable();

		$options = [
			'types' => ['invalid_type1', 'invalid_type2'],
			'subtypes' => [$this->getRandomSubtype(), $this->getRandomSubtype()],
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);

		_elgg_services()->logger->enable();
	}

	public function testElggApiGettersTSPInvalidType() {
		$type_num = 1;
		$types = ['invalid_type1'];

		$pair = [];

		foreach ($types as $type) {
			$pair[$type] = null;
		}

		$options = [
			'type_subtype_pairs' => $pair
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersTSPInvalidTypes() {
		$type_num = 2;
		$types = ['invalid_type1', 'invalid_type2'];

		$pair = [];
		foreach ($types as $type) {
			$pair[$type] = null;
		}

		$options = [
			'type_subtype_pairs' => $pair
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersTSPValidTypeInvalidSubtype() {
		$type_num = 1;
		$subtype_num = 1;
		$types = ['invlaid_type1'];
		$subtypes = [$this->getRandomSubtype()];

		$pair = [$types[0] => $subtypes[0]];

		$options = [
			'type_subtype_pairs' => $pair
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersTSPValidTypeInvalidSubtypes() {
		$type_num = 1;
		$subtype_num = 2;
		$types = ['invlaid_type1'];
		$subtypes = [$this->getRandomSubtype(), $this->getRandomSubtype()];

		$pair = [
			$types[0] => [
				$subtypes[0],
				$subtypes[1]
			]
		];

		$options = [
			'type_subtype_pairs' => $pair
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersTSPValidTypesInvalidSubtypes() {
		$type_num = 2;
		$subtype_num = 2;
		$types = ['invlaid_type1', 'invalid_type2'];
		$subtypes = [$this->getRandomSubtype(), $this->getRandomSubtype()];

		$pair = [];
		foreach ($types as $type) {
			$pair[$type] = $subtypes;
		}

		$options = [
			'type_subtype_pairs' => $pair
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggGetEntitiesByGuidSingular() {
		$entities = $this->createMany(['object', 'group', 'user'], 1);
		foreach ($entities as $e) {
			$options = [
				'guid' => $e->guid
			];
			$es = elgg_get_entities($options);

			$this->assertEqual(count($es), 1);
			$this->assertEqual($es[0]->guid, $e->guid);
		}
	}

	public function testElggGetEntitiesByGuidPlural() {
		$guids = [];

		$entities = $this->createMany(['object', 'group', 'user'], 1);

		foreach ($entities as $e) {
			$guids[] = $e->guid;
		}

		$options = [
			'guids' => $guids,
			'limit' => 100
		];

		$es = elgg_get_entities($options);

		$this->assertEqual(count($es), count($entities));

		foreach ($es as $e) {
			$this->assertTrue(in_array($e->guid, $guids));
		}
	}


	public function testElggGetEntitiesBadWheres() {
		$options = [
			'container_guid' => 'abc'
		];

		$entities = elgg_get_entities($options);
		$this->assertFalse($entities);
	}

	public function testEGEEmptySubtypePlurality() {
		$options = [
			'type' => 'user',
			'subtypes' => ''
		];

		$entities = elgg_get_entities($options);
		$this->assertTrue(is_array($entities));

		$options = [
			'type' => 'user',
			'subtype' => ''
		];

		$entities = elgg_get_entities($options);
		$this->assertTrue(is_array($entities));

		$options = [
			'type' => 'user',
			'subtype' => ['']
		];

		$entities = elgg_get_entities($options);
		$this->assertTrue(is_array($entities));

		$options = [
			'type' => 'user',
			'subtypes' => ['']
		];

		$entities = elgg_get_entities($options);
		$this->assertTrue(is_array($entities));
	}

	public function testDistinctCanBeDisabled() {
		$prefix = _elgg_config()->dbprefix;
		$options = [
			'callback' => '',
			'joins' => [
				"RIGHT JOIN {$prefix}metadata m ON (e.guid = m.entity_guid)"
			],
			'wheres' => [
				'm.entity_guid = ' . elgg_get_logged_in_user_guid(),
			],
		];

		$users = elgg_get_entities($options);
		$this->assertEqual(1, count($users));

		$options['distinct'] = false;
		$users = elgg_get_entities($options);
		$this->assertTrue(count($users) > 1);
	}
	
	public function testElggGetEntitiesWithoutPrivateSettingsPreloader() {
		$entities = $this->createMany('object', 2);
		
		foreach ($entities as $e) {
			$guids[] = $e->guid;
			$e->setPrivateSetting('foo', 'bar');
		}

		$options = [
			'guids' => $guids,
			'limit' => false,
		];

		$es = elgg_get_entities($options);
				
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
		
		foreach ($entities as $e) {
			$guids[] = $e->guid;
			$e->setPrivateSetting('foo', 'bar');
		}

		$options = [
			'guids' => $guids,
			'limit' => false,
			'preload_private_settings' => true,
		];

		$es = elgg_get_entities($options);
				
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
