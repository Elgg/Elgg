<?php

/**
 * Test elgg_get_entities()
 */
class ElggCoreGetEntitiesTest extends \ElggCoreGetEntitiesBaseTest {

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
		$type_arr = $this->getRandomValidTypes();
		$type = $type_arr[0];
		$options = [
			'type' => $type,
			'group_by' => 'e.type'
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

		// should only ever return one object because of group by
		$this->assertIdentical(count($es), 1);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $type_arr));
		}
	}

	public function testElggAPIGettersValidTypeUsingTypesAsString() {
		$type_arr = $this->getRandomValidTypes();
		$type = $type_arr[0];
		$options = [
			'types' => $type,
			'group_by' => 'e.type'
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

		// should only ever return one object because of group by
		$this->assertIdentical(count($es), 1);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $type_arr));
		}
	}

	public function testElggAPIGettersValidTypeUsingTypesAsArray() {
		$type_arr = $this->getRandomValidTypes();
		$type = $type_arr[0];
		$options = [
			'types' => $type_arr,
			'group_by' => 'e.type'
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

		// should only ever return one object because of group by
		$this->assertIdentical(count($es), 1);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $type_arr));
		}
	}

	public function testElggAPIGettersValidTypeUsingTypesAsArrayPlural() {
		$num = 2;
		$types = $this->getRandomValidTypes($num);
		$options = [
			'types' => $types,
			'group_by' => 'e.type'
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

		// one of object and one of group
		$this->assertIdentical(count($es), $num);

		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $types));
		}
	}


	/*
	 * Test mixed valid and invalid types.
	 */


	public function testElggAPIGettersValidAndInvalidTypes() {
		$types = $this->getRandomMixedTypes(2);
		
		$options = [
			'types' => $types,
			'group_by' => 'e.type'
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

		// should only ever return one object because of group by
		$this->assertIdentical(count($es), 1);
		$this->assertIdentical($es[0]->getType(), $valid);
	}

	public function testElggAPIGettersValidAndInvalidTypesPlural() {
		$valid_num = 2;
		$invalid_num = 3;
		$valid = $this->getRandomValidTypes($valid_num);
		$invalid = $this->getRandomInvalids($invalid_num);

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
		$this->assertIsA($es, 'array');

		// should only ever return one object because of group by
		$this->assertIdentical(count($es), $valid_num);
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
		$types = $this->getRandomValidTypes();
		$subtypes = $this->getRandomValidSubtypes($types);
		$subtype = $subtypes[0];

		$options = [
			'types' => $types,
			'subtype' => $subtype
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

		$this->assertIdentical(count($es), 1);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $types));
			$this->assertTrue(in_array($e->getSubtype(), $subtypes));
		}
	}

	public function testElggAPIGettersValidSubtypeUsingSubtypesAsStringSingularType() {
		$types = $this->getRandomValidTypes();
		$subtypes = $this->getRandomValidSubtypes($types);
		$subtype = $subtypes[0];

		$options = [
			'types' => $types,
			'subtypes' => $subtype
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

		$this->assertIdentical(count($es), 1);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $types));
			$this->assertTrue(in_array($e->getSubtype(), $subtypes));
		}
	}

	public function testElggAPIGettersValidSubtypeUsingSubtypesAsArraySingularType() {
		$types = $this->getRandomValidTypes();
		$subtypes = $this->getRandomValidSubtypes($types);

		$options = [
			'types' => $types,
			'subtypes' => $subtypes
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

		$this->assertIdentical(count($es), 1);
		foreach ($es as $e) {
			$this->assertTrue(in_array($e->getType(), $types));
			$this->assertTrue(in_array($e->getSubtype(), $subtypes));
		}
	}

	public function testElggAPIGettersValidSubtypeUsingPluralSubtypesSingularType() {
		$subtype_num = 2;
		$types = $this->getRandomValidTypes();
		$subtypes = $this->getRandomValidSubtypes($types, $subtype_num);

		$options = [
			'types' => $types,
			'subtypes' => $subtypes
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

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
		$type_num = 2;
		$subtype_num = 2;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomValidSubtypes($types, $subtype_num);

		$options = [
			'types' => $types,
			'subtypes' => $subtypes
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

		// this will unset all invalid subtypes for each type that that only
		// one entity exists of each.
		$this->assertIdentical(count($es), $subtype_num);
		foreach ($es as $e) {
			// entities must at least be in the type.
			$this->assertTrue(in_array($e->getType(), $types));

			// test that this is a valid subtype for the entity type.
			$this->assertTrue(in_array($e->getSubtype(), $this->subtypes[$e->getType()]));
		}
	}

	/*
	 * This combination will remove all invalid subtypes for this type.
	 */
	public function testElggAPIGettersValidSubtypeUsingPluralMixedSubtypesSingleType() {
		$type_num = 1;
		$subtype_num = 2;
		$types = $this->getRandomValidTypes($type_num);


		//@todo replace this with $this->getRandomMixedSubtypes()
		// we want this to return an invalid subtype for the returned type.
		$subtype_types = $types;
		$i = 1;
		while ($i <= $subtype_num) {
			$type = $this->types[$i - 1];

			if (!in_array($type, $subtype_types)) {
				$subtype_types[] = $type;
			}
			$i++;
		}

		$subtypes = $this->getRandomValidSubtypes($subtype_types, $type_num);

		$options = [
			'types' => $types,
			'subtypes' => $subtypes
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

		// this will unset all invalid subtypes for each type that that only
		// one entity exists of each.
		$this->assertIdentical(count($es), $type_num);
		foreach ($es as $e) {
			// entities must at least be in the type.
			$this->assertTrue(in_array($e->getType(), $types));

			// test that this is a valid subtype for the entity type.
			$this->assertTrue(in_array($e->getSubtype(), $this->subtypes[$e->getType()]));
		}
	}


	/***************************
	 * TYPE_SUBTYPE_PAIRS
	 ***************************/

	/**
	 * Valid type, valid subtype pairs
	 */
	public function testElggAPIGettersTSPValidTypeValidSubtype() {
		$type_num = 1;
		$subtype_num = 1;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomValidSubtypes($types, $subtype_num);

		$pair = [$types[0] => $subtypes[0]];

		$options = [
			'type_subtype_pairs' => $pair
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

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
		$type_num = 1;
		$subtype_num = 3;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomValidSubtypes($types, $subtype_num);

		$pair = [$types[0] => $subtypes];

		$options = [
			'type_subtype_pairs' => $pair
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

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
		$type_num = 1;
		$valid_subtype_num = 2;
		$types = $this->getRandomValidTypes($type_num);
		$valid = $this->getRandomValidSubtypes($types, $valid_subtype_num);
		$invalid = $this->getRandomInvalids();

		$subtypes = array_merge($valid, $invalid);
		shuffle($subtypes);

		$pair = [$types[0] => $subtypes];

		$options = [
			'type_subtype_pairs' => $pair
		];

		$es = elgg_get_entities($options);
		$this->assertIsA($es, 'array');

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
		$type_arr = $this->getRandomInvalids();
		$type = $type_arr[0];

		$options = [
			'type' => $type
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	/**
	 * Test invalid types with plural 'types'.
	 */
	public function testElggApiGettersInvalidTypeUsingTypesAsString() {
		$type_arr = $this->getRandomInvalids();
		$type = $type_arr[0];

		$options = [
			'types' => $type
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	/**
	 * Test invalid types with plural 'types' and an array of a single type
	 */
	public function testElggApiGettersInvalidTypeUsingTypesAsArray() {
		$type_arr = $this->getRandomInvalids(1);

		$options = [
			'types' => $type_arr
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	/**
	 * Test invalid types with plural 'types' and an array of a two types
	 */
	public function testElggApiGettersInvalidTypes() {
		$type_arr = $this->getRandomInvalids(2);

		$options = [
			'types' => $type_arr
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersInvalidSubtypeValidType() {
		$type_num = 1;
		$subtype_num = 1;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

		$options = [
			'types' => $types,
			'subtypes' => $subtypes
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersInvalidSubtypeValidTypes() {
		$type_num = 2;
		$subtype_num = 1;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

		$options = [
			'types' => $types,
			'subtypes' => $subtypes
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersInvalidSubtypesValidType() {
		$type_num = 1;
		$subtype_num = 2;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

		$options = [
			'types' => $types,
			'subtypes' => $subtypes
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersInvalidSubtypesValidTypes() {
		$type_num = 2;
		$subtype_num = 2;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

		$options = [
			'types' => $types,
			'subtypes' => $subtypes
		];

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersTSPInvalidType() {
		$type_num = 1;
		$types = $this->getRandomInvalids($type_num);

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
		$types = $this->getRandomInvalids($type_num);

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
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

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
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

		$pair = [
			$types[0] => [
				$subtypes[0],
				$subtypes[0]
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
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

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
		foreach ($this->entities as $e) {
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

		foreach ($this->entities as $e) {
			$guids[] = $e->guid;
		}

		$options = [
			'guids' => $guids,
			'limit' => 100
		];

		$es = elgg_get_entities($options);

		$this->assertEqual(count($es), count($this->entities));

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
}
