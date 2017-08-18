<?php

/**
 * Test elgg_get_entities_from_annotations() and
 * elgg_get_entities_from_annotation_calculation()
 */
class ElggCoreGetEntitiesFromAnnotationsTest extends \ElggCoreGetEntitiesBaseTest {

	/**
	 * Creates random annotations on $entity
	 *
	 * @param \ElggEntity $entity
	 * @param int         $max
	 */
	protected function createRandomAnnotations($entity, $max = 1) {
		$annotations = [];
		for ($i = 0; $i < $max; $i++) {
			$name = 'test_annotation_name_' . rand();
			$value = rand();
			$id = create_annotation($entity->getGUID(), $name, $value, 'integer', $entity->getGUID());
			$annotations[] = elgg_get_annotation_from_id($id);
		}

		return $annotations;
	}

	public function testCanGetEntitiesByAnnotationCreationTime() {
		$prefix = _elgg_config()->dbprefix;
		$users = elgg_get_entities([
			'type' => 'user',
			'subtypes' => $this->getRandomValidSubtypes(['user'], 5),
			'limit' => 1
		]);

		// create some test annotations
		$subtypes = $this->getRandomValidSubtypes(['object'], 1);
		$subtype = $subtypes[0];
		$annotation_name = 'test_annotation_name_' . rand();

		// our targets
		$valid1 = new \ElggObject();
		$valid1->subtype = $subtype;
		$valid1->save();
		$id1 = $valid1->annotate($annotation_name, 1, ACCESS_PUBLIC, $users[0]->guid);

		// this one earlier
		$yesterday = time() - 86400;
		update_data("
			UPDATE {$prefix}annotations
			SET time_created = $yesterday
			WHERE id = $id1
		");

		$valid2 = new \ElggObject();
		$valid2->subtype = $subtype;
		$valid2->save();
		$valid2->annotate($annotation_name, 1, ACCESS_PUBLIC, $users[0]->guid);

		$options = [
			'annotation_owner_guid' => $users[0]->guid,
			'annotation_created_time_lower' => (time() - 3600),
			'annotation_name' => $annotation_name,
		];

		$entities = elgg_get_entities_from_annotations($options);

		$this->assertEqual(1, count($entities));
		$this->assertEqual($valid2->guid, $entities[0]->guid);

		$options = [
			'annotation_owner_guid' => $users[0]->guid,
			'annotation_created_time_upper' => (time() - 3600),
			'annotation_name' => $annotation_name,
		];

		$entities = elgg_get_entities_from_annotations($options);

		$this->assertEqual(1, count($entities));
		$this->assertEqual($valid1->guid, $entities[0]->guid);

		$valid1->delete();
		$valid2->delete();
	}

	public function testElggApiGettersEntitiesFromAnnotation() {

		// grab a few different users to annotation
		// there will always be at least 2 here because of the construct.
		$users = elgg_get_entities([
			'type' => 'user',
			'subtypes' => $this->getRandomValidSubtypes(['user'], 5),
			'limit' => 2
		]);

		// create some test annotations
		$subtypes = $this->getRandomValidSubtypes(['object'], 1);
		$subtype = $subtypes[0];
		$annotation_name = 'test_annotation_name_' . rand();
		$annotation_value = rand(1000, 9999);
		$annotation_name2 = 'test_annotation_name_' . rand();
		$annotation_value2 = rand(1000, 9999);
		$guids = [];

		// our targets
		$valid = $this->createObject([
			'subtype' => $subtype,
		]);
		$guids[] = $valid->getGUID();
		create_annotation($valid->getGUID(), $annotation_name, $annotation_value, 'integer', $users[0]->getGUID());

		$valid2 = $this->createObject([
			'subtype' => $subtype,
		]);
		$guids[] = $valid2->getGUID();
		create_annotation($valid2->getGUID(), $annotation_name2, $annotation_value2, 'integer', $users[1]->getGUID());

		$options = [
			'annotation_owner_guid' => $users[0]->getGUID(),
			'annotation_name' => $annotation_name
		];

		$entities = elgg_get_entities_from_annotations($options);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $guids));
			$annotations = $entity->getAnnotations(['annotation_name' => $annotation_name]);
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

	/**
	 * This function tests the deprecated behaviour of egef_annotations
	 * discussed in https://github.com/Elgg/Elgg/issues/6638
	 */
	public function testElggApiGettersEntitiesFromAnnotationOrderByMaxtime() {

		// grab a few different users to annotation
		// there will always be at least 2 here because of the construct.
		$users = elgg_get_entities([
			'type' => 'user',
			'subtypes' => $this->getRandomValidSubtypes(['user'], 5),
			'limit' => 2
		]);

		// create some test annotations
		$subtypes = $this->getRandomValidSubtypes(['object'], 1);
		$subtype = $subtypes[0];
		$annotation_name = 'test_annotation_name_' . rand();
		$annotation_value = rand(1000, 9999);
		$annotation_name2 = 'test_annotation_name_' . rand();
		$annotation_value2 = rand(1000, 9999);
		$guids = [];

		// our targets
		$valid = new \ElggObject();
		$valid->subtype = $subtype;
		$valid->save();
		$guids[] = $valid->getGUID();
		create_annotation($valid->getGUID(), $annotation_name, $annotation_value, 'integer', $users[0]->getGUID());

		$valid2 = new \ElggObject();
		$valid2->subtype = $subtype;
		$valid2->save();
		$guids[] = $valid2->getGUID();
		create_annotation($valid2->getGUID(), $annotation_name2, $annotation_value2, 'integer', $users[1]->getGUID());

		$options = [
			'annotation_owner_guid' => $users[0]->getGUID(),
			'annotation_name' => $annotation_name,
			'selects' => ['MAX(n_table.time_created) AS maxtime'],
			'group_by' => 'n_table.entity_guid',
			'order_by' => 'maxtime'
		];

		$entities = elgg_get_entities_from_annotations($options);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $guids));
			$annotations = $entity->getAnnotations(['annotation_name' => $annotation_name]);
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


	/**
	 * Get entities ordered by various MySQL calculations on their annotations
	 *
	 * @covers elgg_get_entities_from_annotation_calculation()
	 */
	public function testElggGetEntitiesFromAnnotationsCalculateX() {
		$types = [
			'sum',
			'avg',
			'min',
			'max'
		];

		$num_entities = 5;

		// these are chosen to avoid the sums, means, mins, maxs being the same
		// note that the calculation is cast to an int in SQL
		$numbers = [
			[
				0,
				5
			],
			[
				2,
				13
			],
			[
				-3,
				11
			],
			[
				7,
				9
			],
			[
				1.2,
				22
			],
		];

		foreach ($types as $type) {
			$subtypes = $this->getRandomValidSubtypes(['object'], $num_entities);
			$name = "test_annotation_tegefacx_$type";
			$values = [];
			$options = [
				'type' => 'object',
				'subtypes' => $subtypes,
				'limit' => $num_entities,
			];

			$es = elgg_get_entities($options);

			foreach ($es as $index => $e) {
				$e->deleteAnnotations($name);

				$value = $numbers[$index][0];
				$e->annotate($name, $value);

				$value2 = $numbers[$index][1];
				$e->annotate($name, $value2);

				switch ($type) {
					case 'sum':
						$calc_value = $value + $value2;
						break;

					case 'avg':
						$calc_value = ($value + $value2) / 2;
						break;

					case 'min':
						$calc_value = min([
							$value,
							$value2
						]);
						break;

					case 'max':
						$calc_value = max([
							$value,
							$value2
						]);
						break;
				}

				$values[$e->guid] = $calc_value;
			}

			arsort($values);
			$expected_order = array_keys($values);

			$options = [
				'type' => 'object',
				'subtypes' => $subtypes,
				'guids' => array_keys($values),
				'annotation_name' => $name,
				'calculation' => $type
			];

			$es = elgg_get_entities_from_annotation_calculation($options);

			$actual_order = array_map(function($e) {
				return $e->guid;
			}, $es);

			foreach ($es as $i => $e) {
				$value = 0;
				$as = $e->getAnnotations(['annotation_name' => $name]);
				// should only ever be 2
				$this->assertEqual(2, count($as));

				$value = $as[0]->value;
				$value2 = $as[1]->value;

				switch ($type) {
					case 'sum':
						$calc_value = $value + $value2;
						break;

					case 'avg':
						$calc_value = ($value + $value2) / 2;
						break;

					case 'min':
						$calc_value = min([
							$value,
							$value2
						]);
						break;

					case 'max':
						$calc_value = max([
							$value,
							$value2
						]);
						break;
				}

				$this->assertEqual($values[$e->guid], $calc_value);
				$this->assertEqual($calc_value, $e->getVolatileData('select:annotation_calculation'));
			}

			$this->assertEqual($expected_order, $actual_order);

			$options['count'] = true;
			$es_count = elgg_get_entities_from_annotation_calculation($options);
			$this->assertEqual($es_count, $num_entities);
		}
	}

	/**
	 * Get entities ordered by various MySQL calculations on their annotations constrained by a where clause
	 *
	 * @covers elgg_get_entities_from_annotation_calculation()
	 */
	public function testElggGetEntitiesFromAnnotationsCalculateConstrainedByWhere() {

		$num_entities = 3;

		$subtypes = $this->getRandomValidSubtypes(['object'], $num_entities);
		$name = "test_annotation_tegefacxwhere_" . rand(0, 9999);

		$values = [
			[
				-3,
				0,
				5,
				8,
				'foo'
			],
			[
				-8,
				-5,
				-2,
				0,
				1
			],
			[
				-4,
				-2,
				-1,
				'bar'
			]
		];
		$assert_count = 2;

		$options = [
			'type' => 'object',
			'subtypes' => $subtypes,
			'limit' => $num_entities,
		];

		$entities = elgg_get_entities($options);

		$guids = [];

		foreach ($entities as $index => $entity) {
			$entity->deleteAnnotations($name);
			$guids[] = $entity->guid;
			foreach ($values[$index] as $value) {
				$entity->annotate($name, $value);
			}
		}

		$options = [
			'type' => 'object',
			'subtypes' => $subtypes,
			'guids' => $guids,
			'annotation_name' => $name,
			'annotation_values' => array_unique(call_user_func_array('array_merge', $values)),
			'calculation' => 'sum',
			'wheres' => [
				"CAST(n_table.value as SIGNED) > 0"
			]
		];

		$es = elgg_get_entities_from_annotation_calculation($options);

		foreach ($es as $i => $e) {

			$assertion_values = [];
			foreach ($values[$i] as $value) {
				if (is_numeric($value) && $value > 0) {
					$assertion_values[] = $value;
				}
			}
			$annotations = $e->getAnnotations([
				'annotation_name' => $name,
				'where' => ["CAST(n_table.value AS SIGNED) > 0"]
			]);
			if (count($assertion_values)) {
				$this->assertIsA($annotations, 'array');
				$this->assertEqual(count($assertion_values), count($annotations));
			} else {
				$this->assertFalse($annotations);
			}

			$annotation_values = [];
			foreach ($annotations as $ann) {
				$annotation_values[] = $ann->value;
			}

			$this->assertEqual(array_sum($assertion_values), array_sum($annotation_values));
		}

		$options['count'] = true;
		$es_count = elgg_get_entities_from_annotation_calculation($options);
		$this->assertEqual($es_count, $assert_count);
	}

	/**
	 * Get a count of entities using egefac()
	 * Testing to make sure that the count includes each entity with multiple annotations of the same name only once
	 * Irrespective of the calculation type passed
	 *
	 * @covers elgg_get_entities_from_annotation_calculation()
	 */
	public function testElggGetEntitiesFromAnnotationCalculationCount() {
		// add two annotations with a unique name to a set of entities
		// then count the number of entities using egefac()

		$subtypes = $this->getRandomValidSubtypes(['object'], 3);
		$name = 'test_annotation_' . rand(0, 9999);

		$options = [
			'type' => 'object',
			'subtypes' => $subtypes,
			'limit' => 3
		];

		$entities = elgg_get_entities($options);

		foreach ($entities as $entity) {
			$entity->deleteAnnotations($name);
			$value = rand(0, 9999);
			$entity->annotate($name, $value);
			$value = rand(0, 9999);
			$entity->annotate($name, $value);
		}

		$options = [
			'type' => 'object',
			'subtypes' => $subtypes,
			'annotation_name' => $name,
			'count' => true,
		];

		$calculations = [
			'sum',
			'avg',
			'min',
			'max'
		];
		foreach ($calculations as $calculation) {
			$options['calculation'] = $calculation;
			$count = elgg_get_entities_from_annotation_calculation($options);
			$this->assertIdentical(3, $count);
		}
	}

	/**
	 * Get a count of entities annotated with the same value but different annotation names
	 * Irrespective of the calculation
	 *
	 * @covers elgg_get_entities_from_annotation_calculation()
	 */
	public function testElggGetEntitiesFromAnnotationCalculationCountFromAnnotationValues() {

		$subtypes = $this->getRandomValidSubtypes(['object'], 3);
		$value = rand(0, 9999);

		$options = [
			'type' => 'object',
			'subtypes' => $subtypes,
			'limit' => 3
		];

		$es = elgg_get_entities($options);

		foreach ($es as $e) {
			$name = 'test_annotation_egefacval_' . rand(0, 9999);
			$e->deleteAnnotations($name);
			$e->annotate($name, $value);
		}

		$options = [
			'type' => 'object',
			'subtypes' => $subtypes,
			'annotation_value' => $value,
			'count' => true,
		];
		$calculations = [
			'sum',
			'avg',
			'min',
			'max'
		];
		foreach ($calculations as $calculation) {
			$options['calculation'] = $calculation;
			$count = elgg_get_entities_from_annotation_calculation($options);
			$this->assertIdentical(3, $count);
		}
	}

	/**
	 * Get a count of entities annotated with the same name => value annotation pairs
	 * Irrespective of the calculation
	 *
	 * @covers elgg_get_entities_from_annotation_calculation()
	 */
	public function testElggGetEntitiesFromAnnotationCalculationCountFromAnnotationNameValuesPairs() {

		$subtypes = $this->getRandomValidSubtypes(['object'], 3);
		$value = rand(0, 9999);
		$name = 'test_annotation_egefacnv';

		$options = [
			'type' => 'object',
			'subtypes' => $subtypes,
			'limit' => 3
		];

		$es = elgg_get_entities($options);

		foreach ($es as $e) {
			$e->deleteAnnotations($name);
			$e->annotate($name, $value);
		}

		$options = [
			'type' => 'object',
			'subtypes' => $subtypes,
			'annotation_name' => $name,
			'annotation_value' => $value,
			'count' => true,
		];

		$calculations = [
			'sum',
			'avg',
			'min',
			'max'
		];
		foreach ($calculations as $calculation) {
			$options['calculation'] = $calculation;
			$count = elgg_get_entities_from_annotation_calculation($options);
			$this->assertIdentical(3, $count);
		}
	}

	public function testElggGetAnnotationsAnnotationNames() {
		$options = ['annotation_names' => []];
		$a_e_map = [];

		// create test annotations on a few entities.
		for ($i = 0; $i < 3; $i++) {
			do {
				$e = $this->entities[array_rand($this->entities)];
			} while (in_array($e->guid, $a_e_map));
			$annotations = $this->createRandomAnnotations($e);

			foreach ($annotations as $a) {
				$options['annotation_names'][] = $a->name;
				$a_e_map[$a->id] = $e->guid;
			}
		}

		$as = elgg_get_annotations($options);

		$this->assertEqual(count($a_e_map), count($as));

		foreach ($as as $a) {
			$this->assertEqual($a_e_map[$a->id], $a->entity_guid);
		}
	}

	public function testElggGetAnnotationsAnnotationValues() {
		$options = ['annotation_values' => []];
		$a_e_map = [];

		// create test annotations on a few entities.
		for ($i = 0; $i < 3; $i++) {
			do {
				$e = $this->entities[array_rand($this->entities)];
			} while (in_array($e->guid, $a_e_map));
			$annotations = $this->createRandomAnnotations($e);

			foreach ($annotations as $a) {
				$options['annotation_values'][] = $a->value;
				$a_e_map[$a->id] = $e->guid;
			}
		}

		$as = elgg_get_annotations($options);

		$this->assertEqual(count($a_e_map), count($as));

		foreach ($as as $a) {
			$this->assertEqual($a_e_map[$a->id], $a->entity_guid);
		}
	}

	public function testElggGetAnnotationsAnnotationOwnerGuids() {
		$options = ['annotation_owner_guids' => []];
		$a_e_map = [];

		// create test annotations on a single entity
		for ($i = 0; $i < 3; $i++) {
			do {
				$e = $this->entities[array_rand($this->entities)];
			} while (in_array($e->guid, $a_e_map));

			// remove annotations left over from previous tests.
			elgg_delete_annotations(['annotation_owner_guid' => $e->guid]);
			$annotations = $this->createRandomAnnotations($e);

			foreach ($annotations as $a) {
				$options['annotation_owner_guids'][] = $e->guid;
				$a_e_map[$a->id] = $e->guid;
			}
		}

		$as = elgg_get_annotations($options);
		$this->assertEqual(count($a_e_map), count($as));

		foreach ($as as $a) {
			$this->assertEqual($a_e_map[$a->id], $a->owner_guid);
		}
	}

}
