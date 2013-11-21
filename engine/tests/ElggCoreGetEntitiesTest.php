<?php

/**
 * Test elgg_get_entities()
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
		$type_arr = $this->getRandomValidTypes();
		$type = $type_arr[0];
		$options = array(
			'type' => $type,
			'group_by' => 'e.type'
		);

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
		$options = array(
			'types' => $type,
			'group_by' => 'e.type'
		);

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
		$options = array(
			'types' => $type_arr,
			'group_by' => 'e.type'
		);

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
		$options = array(
			'types' => $types,
			'group_by' => 'e.type'
		);

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
		//@todo replace this with $this->getRandomMixedTypes().
		$t = $this->getRandomValidTypes();
		$valid = $t[0];

		$t = $this->getRandomInvalids();
		$invalid = $t[0];
		$options = array(
			'types' => array($invalid, $valid),
			'group_by' => 'e.type'
		);

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

		$types = array();
		foreach ($valid as $t) {
			$types[] = $t;
		}

		foreach ($invalid as $t) {
			$types[] = $t;
		}

		shuffle($types);
		$options = array(
			'types' => $types,
			'group_by' => 'e.type'
		);

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

		$options = array(
			'types' => $types,
			'subtype' => $subtype
		);

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

		$options = array(
			'types' => $types,
			'subtypes' => $subtype
		);

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

		$options = array(
			'types' => $types,
			'subtypes' => $subtypes
		);

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

		$options = array(
			'types' => $types,
			'subtypes' => $subtypes
		);

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

		$options = array(
			'types' => $types,
			'subtypes' => $subtypes
		);

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
			$type = $this->types[$i-1];

			if (!in_array($type, $subtype_types)) {
				$subtype_types[] = $type;
			}
			$i++;
		}

		$subtypes = $this->getRandomValidSubtypes($subtype_types, $type_num);

		$options = array(
			'types' => $types,
			'subtypes' => $subtypes
		);

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

		$pair = array($types[0] => $subtypes[0]);

		$options = array(
			'type_subtype_pairs' => $pair
		);

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

		$pair = array($types[0] => $subtypes);

		$options = array(
			'type_subtype_pairs' => $pair
		);

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

		$pair = array($types[0] => $subtypes);

		$options = array(
			'type_subtype_pairs' => $pair
		);

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

		$options = array(
			'type' => $type
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	/**
	 * Test invalid types with plural 'types'.
	 */
	public function testElggApiGettersInvalidTypeUsingTypesAsString() {
		$type_arr = $this->getRandomInvalids();
		$type = $type_arr[0];

		$options = array(
			'types' => $type
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	/**
	 * Test invalid types with plural 'types' and an array of a single type
	 */
	public function testElggApiGettersInvalidTypeUsingTypesAsArray() {
		$type_arr = $this->getRandomInvalids(1);

		$options = array(
			'types' => $type_arr
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	/**
	 * Test invalid types with plural 'types' and an array of a two types
	 */
	public function testElggApiGettersInvalidTypes() {
		$type_arr = $this->getRandomInvalids(2);

		$options = array(
			'types' => $type_arr
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersInvalidSubtypeValidType() {
		$type_num = 1;
		$subtype_num = 1;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

		$options = array(
			'types' => $types,
			'subtypes' => $subtypes
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersInvalidSubtypeValidTypes() {
		$type_num = 2;
		$subtype_num = 1;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

		$options = array(
			'types' => $types,
			'subtypes' => $subtypes
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersInvalidSubtypesValidType() {
		$type_num = 1;
		$subtype_num = 2;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

		$options = array(
			'types' => $types,
			'subtypes' => $subtypes
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersInvalidSubtypesValidTypes() {
		$type_num = 2;
		$subtype_num = 2;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

		$options = array(
			'types' => $types,
			'subtypes' => $subtypes
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersTSPInvalidType() {
		$type_num = 1;
		$types = $this->getRandomInvalids($type_num);

		$pair = array();

		foreach ($types as $type) {
			$pair[$type] = null;
		}

		$options = array(
			'type_subtype_pairs' => $pair
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersTSPInvalidTypes() {
		$type_num = 2;
		$types = $this->getRandomInvalids($type_num);

		$pair = array();
		foreach ($types as $type) {
			$pair[$type] = null;
		}

		$options = array(
			'type_subtype_pairs' => $pair
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersTSPValidTypeInvalidSubtype() {
		$type_num = 1;
		$subtype_num = 1;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

		$pair = array($types[0] => $subtypes[0]);

		$options = array(
			'type_subtype_pairs' => $pair
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersTSPValidTypeInvalidSubtypes() {
		$type_num = 1;
		$subtype_num = 2;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

		$pair = array($types[0] => array($subtypes[0], $subtypes[0]));

		$options = array(
			'type_subtype_pairs' => $pair
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersTSPValidTypesInvalidSubtypes() {
		$type_num = 2;
		$subtype_num = 2;
		$types = $this->getRandomValidTypes($type_num);
		$subtypes = $this->getRandomInvalids($subtype_num);

		$pair = array();
		foreach ($types as $type) {
			$pair[$type] = $subtypes;
		}

		$options = array(
			'type_subtype_pairs' => $pair
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersEntityNoSubtype() {
		// create an entity we can later delete.
		// order by guid and limit by 1 should == this entity.

		$e = new ElggObject();
		$e->save();

		$options = array(
			'type' => 'object',
			'limit' => 1,
			'order_by' => 'guid desc'
		);

		// grab ourself again to fill out attributes.
		$e = get_entity($e->getGUID());

		$entities = elgg_get_entities($options);

		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertIdentical($e->getGUID(), $entity->getGUID());
		}

		$e->delete();
	}

	public function testElggApiGettersEntityNoValueSubtypeNotSet() {
		// create an entity we can later delete.
		// order by time created and limit by 1 should == this entity.

		$e = new ElggObject();
		$e->save();

		$options = array(
			'type' => 'object',
			'subtype' => ELGG_ENTITIES_NO_VALUE,
			'limit' => 1,
			'order_by' => 'guid desc'
		);

		// grab ourself again to fill out attributes.
		$e = get_entity($e->getGUID());

		$entities = elgg_get_entities($options);

		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertIdentical($e->getGUID(), $entity->getGUID());
		}

		$e->delete();
	}

	public function testElggApiGettersEntityNoValueSubtypeSet() {
		global $CONFIG;
		// create an entity we can later delete.
		// order by time created and limit by 1 should == this entity.

		$subtype = 'subtype_' . rand();

		$e_subtype = new ElggObject();
		$e_subtype->subtype = $subtype;
		$e_subtype->save();

		$e = new ElggObject();
		$e->save();

		$options = array(
			'type' => 'object',
			'subtype' => ELGG_ENTITIES_NO_VALUE,
			'limit' => 1,
			'order_by' => 'guid desc'
		);

		// grab ourself again to fill out attributes.
		$e = get_entity($e->getGUID());

		$entities = elgg_get_entities($options);

		$this->assertEqual(count($entities), 1);

		// this entity should NOT be the entity we just created
		// and should have no subtype
		foreach ($entities as $entity) {
			$this->assertEqual($entity->subtype_id, 0);
		}

		$e_subtype->delete();
		$e->delete();

		$q = "DELETE FROM {$CONFIG->dbprefix}entity_subtypes WHERE subtype = '$subtype'";
		delete_data($q);
	}

	public function testElggApiGettersEntitySiteSingular() {
		global $CONFIG;

		$guids = array();

		$obj1 = new ElggObject();
		$obj1->test_md = 'test';
		// luckily this is never checked.
		$obj1->site_guid = 2;
		$obj1->save();
		$guids[] = $obj1->guid;
		$right_guid = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->test_md = 'test';
		$obj2->site_guid = $CONFIG->site->guid;
		$obj2->save();
		$guids[] = $obj2->guid;

		$options = array(
			'metadata_name' => 'test_md',
			'metadata_value' => 'test',
			'site_guid' => 2
		);

		$es = elgg_get_entities_from_metadata($options);
		$this->assertTrue(is_array($es));
		$this->assertEqual(1, count($es));
		$this->assertEqual($right_guid, $es[0]->guid);

		foreach ($guids as $guid) {
			get_entity($guid)->delete();
		}
	}

	public function testElggApiGettersEntitySiteSingularAny() {
		global $CONFIG;

		$guids = array();

		$obj1 = new ElggObject();
		$obj1->test_md = 'test';
		// luckily this is never checked.
		$obj1->site_guid = 2;
		$obj1->save();
		$guids[] = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->test_md = 'test';
		$obj2->site_guid = $CONFIG->site->guid;
		$obj2->save();
		$guids[] = $obj2->guid;

		$options = array(
			'metadata_name' => 'test_md',
			'metadata_value' => 'test',
			'site_guid' => ELGG_ENTITIES_ANY_VALUE,
			'limit' => 2,
			'order_by' => 'e.guid DESC'
		);

		$es = elgg_get_entities_from_metadata($options);
		$this->assertTrue(is_array($es));
		$this->assertEqual(2, count($es));

		foreach ($es as $e) {
			$this->assertTrue(in_array($e->guid, $guids));
		}

		foreach ($guids as $guid) {
			get_entity($guid)->delete();
		}
	}

	public function testElggApiGettersEntitySitePlural() {
		global $CONFIG;

		$guids = array();

		$obj1 = new ElggObject();
		$obj1->test_md = 'test';
		// luckily this is never checked.
		$obj1->site_guid = 2;
		$obj1->save();
		$guids[] = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->test_md = 'test';
		$obj2->site_guid = $CONFIG->site->guid;
		$obj2->save();
		$guids[] = $obj2->guid;

		$options = array(
			'metadata_name' => 'test_md',
			'metadata_value' => 'test',
			'site_guids' => array($CONFIG->site->guid, 2),
			'limit' => 2,
			'order_by' => 'e.guid DESC'
		);

		$es = elgg_get_entities_from_metadata($options);
		$this->assertTrue(is_array($es));
		$this->assertEqual(2, count($es));

		foreach ($es as $e) {
			$this->assertTrue(in_array($e->guid, $guids));
		}

		foreach ($guids as $guid) {
			get_entity($guid)->delete();
		}
	}

	public function testElggApiGettersEntitySitePluralSomeInvalid() {
		global $CONFIG;

		$guids = array();

		$obj1 = new ElggObject();
		$obj1->test_md = 'test';
		// luckily this is never checked.
		$obj1->site_guid = 2;
		$obj1->save();
		$guids[] = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->test_md = 'test';
		$obj2->save();
		$guids[] = $obj2->guid;
		$right_guid = $obj2->guid;

		$options = array(
			'metadata_name' => 'test_md',
			'metadata_value' => 'test',
			// just created the first entity so nothing will be "sited" by it.
			'site_guids' => array($CONFIG->site->guid, $guids[0]),
			'limit' => 2,
			'order_by' => 'e.guid DESC'
		);

		$es = elgg_get_entities_from_metadata($options);

		$this->assertTrue(is_array($es));
		$this->assertEqual(1, count($es));
		$this->assertEqual($es[0]->guid, $right_guid);

		foreach ($guids as $guid) {
			get_entity($guid)->delete();
		}
	}

	public function testElggApiGettersEntitySitePluralAllInvalid() {
		global $CONFIG;

		$guids = array();

		$obj1 = new ElggObject();
		$obj1->test_md = 'test';
		// luckily this is never checked.
		$obj1->site_guid = 2;
		$obj1->save();
		$guids[] = $obj1->guid;

		$obj2 = new ElggObject();
		$obj2->test_md = 'test';
		$obj2->save();
		$guids[] = $obj2->guid;
		$right_guid = $obj2->guid;

		$options = array(
			'metadata_name' => 'test_md',
			'metadata_value' => 'test',
			// just created the first entity so nothing will be "sited" by it.
			'site_guids' => array($guids[0], $guids[1]),
			'limit' => 2,
			'order_by' => 'e.guid DESC'
		);

		$es = elgg_get_entities_from_metadata($options);

		$this->assertTrue(empty($es));

		foreach ($guids as $guid) {
			get_entity($guid)->delete();
		}
	}

	public function testElggGetEntitiesByGuidSingular() {
		foreach ($this->entities as $e) {
			$options = array(
				'guid' => $e->guid
			);
			$es = elgg_get_entities($options);

			$this->assertEqual(count($es), 1);
			$this->assertEqual($es[0]->guid, $e->guid);
		}
	}

	public function testElggGetEntitiesByGuidPlural() {
		$guids = array();

		foreach ($this->entities as $e) {
			$guids[] = $e->guid;
		}

		$options = array(
			'guids' => $guids,
			'limit' => 100
		);

		$es = elgg_get_entities($options);

		$this->assertEqual(count($es), count($this->entities));

		foreach ($es as $e) {
			$this->assertTrue(in_array($e->guid, $guids));
		}
	}


	public function testElggGetEntitiesBadWheres() {
		$options = array(
			'container_guid' => 'abc'
		);

		$entities = elgg_get_entities($options);
		$this->assertFalse($entities);
	}

	public function testEGEEmptySubtypePlurality() {
		$options = array(
			'type' => 'user',
			'subtypes' => ''
		);

		$entities = elgg_get_entities($options);
		$this->assertTrue(is_array($entities));

		$options = array(
			'type' => 'user',
			'subtype' => ''
		);

		$entities = elgg_get_entities($options);
		$this->assertTrue(is_array($entities));

		$options = array(
			'type' => 'user',
			'subtype' => array('')
		);

		$entities = elgg_get_entities($options);
		$this->assertTrue(is_array($entities));

		$options = array(
			'type' => 'user',
			'subtypes' => array('')
		);

		$entities = elgg_get_entities($options);
		$this->assertTrue(is_array($entities));
	}
}
