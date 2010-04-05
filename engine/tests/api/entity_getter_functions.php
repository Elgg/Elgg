<?php

/**
 * Elgg Test Entity Getter Functions
 * @package Elgg
 * @subpackage Test
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
class ElggCoreEntityGetterFunctionsTest extends ElggCoreUnitTest {
	/**
	 * Called before each test object.
	 */
	public function __construct() {
	elgg_set_ignore_access(TRUE);
		$this->entities = array();
		$this->subtypes = array(
			'object' => array(),
			'user' => array(),
			'group' => array(),
			//'site'	=> array()
		);

		// sites are a bit wonky.  Don't use them just now.
		$this->types = array('object', 'user', 'group');

		// create some fun objects to play with.
		// 5 with random subtypes
		for ($i=0; $i<5; $i++) {
			$subtype = 'test_object_subtype_' . rand();
			$e = new ElggObject();
			$e->subtype = $subtype;
			$e->save();

			$this->entities[] = $e;
			$this->subtypes['object'][] = $subtype;
		}

		// and users
		for ($i=0; $i<5; $i++) {
			$subtype = "test_user_subtype_" . rand();
			$e = new ElggUser();
			$e->username = "test_user_" . rand();
			$e->subtype = $subtype;
			$e->save();

			$this->entities[] = $e;
			$this->subtypes['user'][] = $subtype;
		}

		// and groups
		for ($i=0; $i<5; $i++) {
			$subtype = "test_group_subtype_" . rand();
			$e = new ElggGroup();
			$e->subtype = $subtype;
			$e->save();

			$this->entities[] = $e;
			$this->subtypes['group'][] = $subtype;
		}

		parent::__construct();
	}

	/**
	 * Called after each test method.
	 */
	public function setUp() {
		return TRUE;
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		return TRUE;
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		global $CONFIG;

		$this->swallowErrors();
		foreach ($this->entities as $e) {
			$e->delete();
		}

		// manually remove subtype entries since there is no way
		// to using the API.
		$subtype_arr = array();
		foreach ($this->subtypes as $type => $subtypes) {
			foreach ($subtypes as $subtype) {
				$subtype_arr[] = "'$subtype'";
			}
		}

		$subtype_str = implode(',', $subtype_arr);
		$q = "DELETE FROM {$CONFIG->dbprefix}entity_subtypes WHERE subtype IN ($subtype_str)";
		delete_data($q);

		parent::__destruct();
	}


	/*************************************************
	 * Helpers for getting random types and subtypes *
	 *************************************************/

	/**
	 * Get a random valid subtype
	 *
	 * @param int $num
	 * @return array
	 */
	public function getRandomValidTypes($num = 1) {
		$r = array();

		for ($i=1; $i<=$num; $i++) {
			do {
				$t = $this->types[array_rand($this->types)];
			} while (in_array($t, $r) && count($r) < count($this->types));

			$r[] = $t;
		}

		shuffle($r);
		return $r;
	}

	/**
	 * Get a random valid subtype (that we just created)
	 *
	 * @param array $type Type of objects to return valid subtypes for.
	 * @param int $num of subtypes.
	 *
	 * @return array
	 */
	public function getRandomValidSubtypes(array $types, $num = 1) {
		$r = array();

		for ($i=1; $i<=$num; $i++) {
			do {
				// make sure at least one subtype of each type is returned.
				if ($i-1 < count($types)) {
					$type = $types[$i-1];
				} else {
					$type = $types[array_rand($types)];
				}

				$k = array_rand($this->subtypes[$type]);
				$t = $this->subtypes[$type][$k];
			} while (in_array($t, $r));

			$r[] = $t;
		}

		shuffle($r);
		return $r;
	}

	/**
	 * Return an array of invalid strings for type or subtypes.
	 *
	 * @param int $num
	 * @return arr
	 */
	public function getRandomInvalids($num = 1) {
		$r = array();

		for ($i=1; $i<=$num; $i++) {
			$r[] = 'random_invalid_' . rand();
		}

		return $r;
	}

	/**
	 *
	 * @param unknown_type $num
	 * @return unknown_type
	 */
	public function getRandomMixedTypes($num = 2) {
		$have_valid = $have_invalid = false;
		$r = array();

		// need at least one of each type.
		$valid_n = rand(1, $num-1);
		$r = array_merge($r, $this->getRandomValidTypes($valid_n));
		$r = array_merge($r, $this->getRandomInvalids($num - $valid_n));

		shuffle($r);
		return $r;
	}

	/**
	 * Get random mix of valid and invalid subtypes for types given.
	 *
	 * @param array $types
	 * @param unknown_type $num
	 * @return unknown_type
	 */
	public function getRandomMixedSubtypes(array $types, $num = 2) {
		$types_c = count($types);
		$r = array();

		// this can be more efficient but I'm very sleepy...

		// want at least one of valid and invalid of each type sent.
		for ($i=0; $i < $types_c && $num > 0; $i++) {
			// make sure we have a valid and invalid for each type
			if (true) {
				$type = $types[$i];
				$r = array_merge($r, $this->getRandomValidSubtypes(array($type), 1));
				$r = array_merge($r, $this->getRandomInvalids(1));

				$num -= 2;
			}
		}

		if ($num > 0) {
			$valid_n = rand(1, $num);
			$r = array_merge($r, $this->getRandomValidSubtypes($types, $valid_n));
			$r = array_merge($r, $this->getRandomInvalids($num - $valid_n));
		}

		//shuffle($r);
		return $r;
	}


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
	 * FALSE-RETURNING TESTS
	 ****************************
	 * The original bug corrected returned
	 * all entities when invalid subtypes were passed.
	 * Because there's a huge numer of combinations that
	 * return entities, I'm only writing tests for
	 * things that should return false.
	 *
	 * I'm leaving the above in case anyone is inspired to
	 * write out the rest of the possible combinations
	 */


	/*
	 * Test invalid types.
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


	public function testElggApiGettersInvalidTypeUsingTypesAsString() {
		$type_arr = $this->getRandomInvalids();
		$type = $type_arr[0];

		$options = array(
			'types' => $type
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

	public function testElggApiGettersInvalidTypeUsingTypesAsArray() {
		$type_arr = $this->getRandomInvalids();

		$options = array(
			'types' => $type_arr
		);

		$es = elgg_get_entities($options);
		$this->assertFalse($es);
	}

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
			$pair[$type] = NULL;
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
			$pair[$type] = NULL;
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
		// order by time created and limit by 1 should == this entity.

		$e = new ElggObject();
		$e->save();

		$options = array(
			'type' => 'object',
			'limit' => 1,
			'order_by' => 'e.time_created desc'
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
			'order_by' => 'e.time_created desc'
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
			'order_by' => 'e.time_created desc'
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



	/************
	 * METADATA
	 ************/

	//names

	function testElggApiGettersEntityMetadataNameValidSingle() {
		// create a new entity with a subtype we know
		// use an existing type so it will clean up automatically
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_name
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $e->getGUID());
			$this->assertEqual($entity->$md_name, $md_value);
		}

		$e->delete();
	}

	function testElggApiGettersEntityMetadataNameValidMultiple() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_names = array();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_names[] = $md_name;
		$e_guids = array();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->getGUID();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_names[] = $md_name;

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->getGUID();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_names' => $md_names
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 2);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $e_guids));
			$entity->delete();
		}
	}

	function testElggApiGettersEntityMetadataNameInvalidSingle() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();

		$md_invalid_name = 'test_metadata_name_' . rand();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_invalid_name
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertFalse($entities);

		$e->delete();
	}

	function testElggApiGettersEntityMetadataNameInvalidMultiple() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();

		$md_invalid_names = array();
		$md_invalid_names[] = 'test_metadata_name_' . rand();
		$md_invalid_names[] = 'test_metadata_name_' . rand();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_names' => $md_invalid_names
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertFalse($entities);

		$e->delete();
	}


	function testElggApiGettersEntityMetadataNameMixedMultiple() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_names = array();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_names[] = $md_name;
		$e_guids = array();

		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->save();
		$e_guids[] = $valid->getGUID();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		// add a random invalid name.
		$md_names[] = 'test_metadata_name_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->getGUID();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_names' => $md_names
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $valid->getGUID());
		}

		foreach ($e_guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}


	// values
	function testElggApiGettersEntityMetadataValueValidSingle() {
		// create a new entity with a subtype we know
		// use an existing type so it will clean up automatically
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_value' => $md_value
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $e->getGUID());
			$this->assertEqual($entity->$md_name, $md_value);
		}

		$e->delete();
	}

	function testElggApiGettersEntityMetadataValueValidMultiple() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_values = array();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_values[] = $md_value;
		$e_guids = array();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->getGUID();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_values[] = $md_value;

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->getGUID();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_values' => $md_values
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 2);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $e_guids));
			$entity->delete();
		}
	}

	function testElggApiGettersEntityMetadatavalueInvalidSingle() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();

		$md_invalid_value = 'test_metadata_value_' . rand();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_value' => $md_invalid_value
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertFalse($entities);

		$e->delete();
	}

	function testElggApiGettersEntityMetadataValueInvalidMultiple() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();

		$md_invalid_values = array();
		$md_invalid_values[] = 'test_metadata_value_' . rand();
		$md_invalid_values[] = 'test_metadata_value_' . rand();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_values' => $md_invalid_values
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertFalse($entities);

		$e->delete();
	}


	function testElggApiGettersEntityMetadataValueMixedMultiple() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_values = array();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$md_values[] = $md_value;
		$e_guids = array();

		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->save();
		$e_guids[] = $valid->getGUID();

		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		// add a random invalid value.
		$md_values[] = 'test_metadata_value_' . rand();

		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $md_value;
		$e->save();
		$e_guids[] = $e->getGUID();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_values' => $md_values
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $valid->getGUID());
		}

		foreach ($e_guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}


	// name_value_pairs


	function testElggApiGettersEntityMetadataNVPValidNValidVEquals() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = array();

		// our target
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->save();
		$guids[] = $valid->getGUID();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$md_invalid_names = array();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => array(array(
				'name' => $md_name,
				'value' => $md_value
			))
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $valid->getGUID());
			$this->assertEqual($entity->$md_name, $md_value);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	function testElggApiGettersEntityMetadataNVPValidNValidVEqualsTriple() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$md_name3 = 'test_metadata_name_' . rand();
		$md_value3 = 'test_metadata_value_' . rand();

		$guids = array();

		// our target
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->$md_name2 = $md_value2;
		$valid->$md_name3 = $md_value3;
		$valid->save();
		$guids[] = $valid->getGUID();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->$md_name2 = $invalid_md_value;
		$e->$md_name3 = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->$md_name2 = $invalid_md_value;
		$e->$md_name3 = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$md_invalid_names = array();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => array(
				array(
					'name' => $md_name,
					'value' => $md_value
				),
				array(
					'name' => $md_name2,
					'value' => $md_value2
				),
				array(
					'name' => $md_name3,
					'value' => $md_value3
				)
			)
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $valid->getGUID());
			$this->assertEqual($entity->$md_name, $md_value);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	function testElggApiGettersEntityMetadataNVPValidNValidVEqualsDouble() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$guids = array();

		// our target
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->$md_name2 = $md_value2;
		$valid->save();
		$guids[] = $valid->getGUID();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->$md_name2 = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->$md_name2 = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$md_invalid_names = array();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => array(
				array(
					'name' => $md_name,
					'value' => $md_value
				),
				array(
					'name' => $md_name2,
					'value' => $md_value2
				)
			)
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $valid->getGUID());
			$this->assertEqual($entity->$md_name, $md_value);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	function testElggApiGettersEntityMetadataNVPValidNValidVEqualsStupid() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$md_name3 = 'test_metadata_name_' . rand();
		$md_value3 = 'test_metadata_value_' . rand();

		$md_name3 = 'test_metadata_name_' . rand();
		$md_value3 = 'test_metadata_value_' . rand();

		$md_name4 = 'test_metadata_name_' . rand();
		$md_value4 = 'test_metadata_value_' . rand();

		$md_name5 = 'test_metadata_name_' . rand();
		$md_value5 = 'test_metadata_value_' . rand();

		$guids = array();

		// our target
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->$md_name2 = $md_value2;
		$valid->$md_name3 = $md_value3;
		$valid->$md_name4 = $md_value4;
		$valid->$md_name5 = $md_value5;
		$valid->save();
		$guids[] = $valid->getGUID();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->$md_name2 = $invalid_md_value;
		$e->$md_name3 = $invalid_md_value;
		$e->$md_name4 = $invalid_md_value;
		$e->$md_name5 = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->$md_name2 = $invalid_md_value;
		$e->$md_name3 = $invalid_md_value;
		$e->$md_name4 = $invalid_md_value;
		$e->$md_name5 = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$md_invalid_names = array();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => array(
				array(
					'name' => $md_name,
					'value' => $md_value
				),
				array(
					'name' => $md_name2,
					'value' => $md_value2
				),
				array(
					'name' => $md_name3,
					'value' => $md_value3
				),
				array(
					'name' => $md_name4,
					'value' => $md_value4
				),
				array(
					'name' => $md_name5,
					'value' => $md_value5
				),
			)
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 1);

		foreach ($entities as $entity) {
			$this->assertEqual($entity->getGUID(), $valid->getGUID());
			$this->assertEqual($entity->$md_name, $md_value);
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	function testElggApiGettersEntityMetadataNVPValidNInvalidV() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = array();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$md_invalid_names = array();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => array(array(
				'name' => $md_name,
				'value' => 'test_metadata_value_' . rand()
			))
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertFalse($entities);

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}


	function testElggApiGettersEntityMetadataNVPInvalidNValidV() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = array();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$md_invalid_names = array();

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => array(array(
				'name' => 'test_metadata_name_' . rand(),
				'value' => $md_value
			))
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertFalse($entities);

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}


	function testElggApiGettersEntityMetadataNVPValidNValidVOperandIn() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = array();
		$valid_guids = array();

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->$md_name2 = $md_value2;
		$valid2->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid2->getGUID();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$md_valid_values = "'$md_value', '$md_value2'";

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => array(
				array(
					'name' => $md_name,
					'value' => $md_valid_values,
					'operand' => 'IN'
				),
				array(
					'name' => $md_name2,
					'value' => $md_valid_values,
					'operand' => 'IN'
				),
			),
			'metadata_name_value_pairs_operator' => 'OR'
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 2);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	function testElggApiGettersEntityMetadataNVPValidNValidVPlural() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'test_metadata_value_' . rand();
		$guids = array();
		$valid_guids = array();

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$md_name2 = 'test_metadata_name_' . rand();
		$md_value2 = 'test_metadata_value_' . rand();

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->$md_name2 = $md_value2;
		$valid2->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid2->getGUID();

		// make some bad ones
		$invalid_md_name = 'test_metadata_name_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$invalid_md_value = 'test_metadata_value_' . rand();
		$e = new ElggObject();
		$e->subtype = $subtype;
		$e->$md_name = $invalid_md_value;
		$e->save();
		$guids[] = $e->getGUID();

		$md_valid_values = array($md_value, $md_value2);

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name_value_pairs' => array(
				array(
					'name' => $md_name,
					'value' => $md_valid_values,
					'operand' => 'IN'
				),
				array(
					'name' => $md_name2,
					'value' => $md_valid_values,
					'operand' => 'IN'
				),
			),
			'metadata_name_value_pairs_operator' => 'OR'
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 2);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	function testElggApiGettersEntityMetadataNVPOrderByMDText() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 2;
		$guids = array();
		$valid_guids = array();

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->$md_name = 3;
		$valid2->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid2->getGUID();

		$valid3 = new ElggObject();
		$valid3->subtype = $subtype;
		$valid3->$md_name = 1;
		$valid3->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid3->getGUID();

		$md_valid_values = array($md_value, $md_value2);

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			//'metadata_name' => $md_name,
			'order_by_metadata' => array('name' => $md_name, 'as' => 'integer')
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 3);

		$i = 1;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$this->assertEqual($entity->$md_name, $i);
			$i++;
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	function testElggApiGettersEntityMetadataNVPOrderByMDString() {
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$md_name = 'test_metadata_name_' . rand();
		$md_value = 'b';
		$guids = array();
		$valid_guids = array();

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->$md_name = $md_value;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid->getGUID();

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->$md_name = 'c';
		$valid2->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid2->getGUID();

		$valid3 = new ElggObject();
		$valid3->subtype = $subtype;
		$valid3->$md_name = 'a';
		$valid3->save();
		$guids[] = $valid->getGUID();
		$valid_guids[] = $valid3->getGUID();

		$md_valid_values = array($md_value, $md_value2);

		$options = array(
			'type' => 'object',
			'subtype' => $subtype,
			'metadata_name' => $md_name,
			'order_by_metadata' => array('name' => $md_name, 'as' => 'text')
		);

		$entities = elgg_get_entities_from_metadata($options);

		$this->assertIsa($entities, 'array');
		$this->assertEqual(count($entities), 3);

		$alpha = array('a', 'b', 'c');

		$i = 0;
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $valid_guids));
			$this->assertEqual($entity->$md_name, $alpha[$i]);
			$i++;
			$entity->delete();
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}

	/**
	 * Annotations
	 */
	public function testElggApiGettersEntitiesFromAnnotation() {

		// grab a few different users to annotation
		// there will always be at least 2 here because of the construct.
		$users = elgg_get_entities(array('type' => 'user', 'limit' => 2));

		// create some test annotations
		$subtypes = $this->getRandomValidSubtypes(array('object'), 1);
		$subtype = $subtypes[0];
		$annotation_name = 'test_annotation_name_' . rand();
		$annotation_value = rand(1000, 9999);
		$annotation_name2 = 'test_annotation_name_' . rand();
		$annotation_value2 = rand(1000, 9999);
		$guids = array();

		// our targets
		$valid = new ElggObject();
		$valid->subtype = $subtype;
		$valid->save();
		$guids[] = $valid->getGUID();
		create_annotation($valid->getGUID(), $annotation_name, $annotation_value, 'integer', $users[0]->getGUID());

		$valid2 = new ElggObject();
		$valid2->subtype = $subtype;
		$valid2->save();
		$guids[] = $valid2->getGUID();
		create_annotation($valid2->getGUID(), $annotation_name2, $annotation_value2, 'integer', $users[1]->getGUID());

		$options = array(
			'annotation_owner_guid' => $users[0]->getGUID(),
			'annotation_name' => $annotation_name
		);

		$entities = elgg_get_entities_from_annotations($options);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $guids));
			$annotations = $entity->getAnnotations($annotation_name);
			$this->assertEqual(count($annotations), 1);

			$this->assertEqual($annotations[0]->name, $annotation_name);
			$this->assertEqual($annotations[0]->value, $annotation_value);
			$this->assertEqual($annotations[0]->owner_guid, $users[0]->getGUID());
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}
}
