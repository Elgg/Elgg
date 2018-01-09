<?php

namespace Elgg\Integration;

/**
 * Test elgg_get_entities_from_annotations() and
 * elgg_get_entities_from_annotation_calculation()
 *
 * @group IntegrationTests
 * @group Entities
 * @group EntityAnnotations
 */
class ElggCoreGetEntitiesFromAnnotationsTest extends ElggCoreGetEntitiesBaseTest {

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

		$user1 = $this->createOne('user');

		$prefix = _elgg_config()->dbprefix;

		$annotation_name = 'test_annotation_name_' . rand();

		// our targets
		$valid1 = $this->createOne();
		$id1 = $valid1->annotate($annotation_name, 1, ACCESS_PUBLIC, $user1->guid);

		// this one earlier
		$yesterday = time() - 86400;
		update_data("
			UPDATE {$prefix}annotations
			SET time_created = $yesterday
			WHERE id = $id1
		");

		$valid2 = $this->createOne();
		$valid2->annotate($annotation_name, 1, ACCESS_PUBLIC, $user1->guid);

		$options = [
			'annotation_owner_guid' => $user1->guid,
			'annotation_created_time_lower' => (time() - 3600),
			'annotation_name' => $annotation_name,
		];

		$entities = elgg_get_entities_from_annotations($options);

		$this->assertEqual(1, count($entities));
		$this->assertEqual($valid2->guid, $entities[0]->guid);

		$options = [
			'annotation_owner_guid' => $user1->guid,
			'annotation_created_time_upper' => (time() - 3600),
			'annotation_name' => $annotation_name,
		];

		$entities = elgg_get_entities_from_annotations($options);

		$this->assertEqual(1, count($entities));
		$this->assertEqual($valid1->guid, $entities[0]->guid);
	}

	public function testElggApiGettersEntitiesFromAnnotation() {

		$user1 = $this->createOne('user');
		$user2 = $this->createOne('user');

		// create some test annotations
		$annotation_name = 'test_annotation_name_' . rand();
		$annotation_value = rand(1000, 9999);
		$annotation_name2 = 'test_annotation_name_' . rand();
		$annotation_value2 = rand(1000, 9999);
		$guids = [];

		// our targets
		$valid = $this->createOne();

		$guids[] = $valid->getGUID();
		create_annotation($valid->getGUID(), $annotation_name, $annotation_value, 'integer', $user1->getGUID());

		$valid2 = $this->createOne();
		$guids[] = $valid2->getGUID();
		create_annotation($valid2->getGUID(), $annotation_name2, $annotation_value2, 'integer', $user2->getGUID());

		$options = [
			'annotation_owner_guid' => $user1->getGUID(),
			'annotation_name' => $annotation_name
		];

		$entities = elgg_get_entities_from_annotations($options);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $guids));
			$annotations = $entity->getAnnotations(['annotation_name' => $annotation_name]);
			$this->assertEqual(count($annotations), 1);

			$this->assertEqual($annotations[0]->name, $annotation_name);
			$this->assertEqual($annotations[0]->value, $annotation_value);
			$this->assertEqual($annotations[0]->owner_guid, $user1->getGUID());
		}
	}

	/**
	 * This function tests the deprecated behaviour of egef_annotations
	 * discussed in https://github.com/Elgg/Elgg/issues/6638
	 */
	public function testElggApiGettersEntitiesFromAnnotationOrderByMaxtime() {

		$user1 = $this->createOne('user');
		$user2 = $this->createOne('user');

		// create some test annotations
		$annotation_name = 'test_annotation_name_' . rand();
		$annotation_value = rand(1000, 9999);
		$annotation_name2 = 'test_annotation_name_' . rand();
		$annotation_value2 = rand(1000, 9999);
		$guids = [];

		// our targets
		$valid = $this->createOne();

		$guids[] = $valid->getGUID();
		create_annotation($valid->getGUID(), $annotation_name, $annotation_value, 'integer', $user1->getGUID());

		$valid2 = $this->createOne();
		$guids[] = $valid2->getGUID();
		create_annotation($valid2->getGUID(), $annotation_name2, $annotation_value2, 'integer', $user2->getGUID());

		$options = [
			'annotation_owner_guid' => $user1->getGUID(),
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
			$this->assertEqual($annotations[0]->owner_guid, $user1->getGUID());
		}

	}


	/**
	 * Get entities ordered by various MySQL calculations on their annotations
	 *
	 * @dataProvider calculationTypesProvider
	 */
	public function testElggGetEntitiesFromAnnotationsCalculateX($type) {

		$num_entities = 5;

		// these are chosen to avoid the sums, means, mins, maxs being the same
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

		$name = "test_annotation_tegefacx_$type";

		$es = $this->createMany('object', $num_entities);

		foreach ($es as $index => $e) {

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
			'guids' => array_keys($values),
			'annotation_name' => $name,
			'calculation' => $type
		];

		$es = elgg_get_entities_from_annotation_calculation($options);

		$actual_order = array_map(function ($e) {
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

	public function calculationTypesProvider() {
		return [
			['sum'],
			['avg'],
			['min'],
			['max'],
		];
	}

	/**
	 * Get entities ordered by various MySQL calculations on their annotations constrained by a where clause
	 *
	 * @group  AnnotationCalculation
	 */
	public function testElggGetEntitiesFromAnnotationsCalculateConstrainedByWhere() {

		$num_entities = 3;

		$es = $this->createMany('object', $num_entities);
		$subtypes = array_map(function ($e) {
			return $e->getSubtype();
		}, $es);

		$name = "test_annotation_tegefacxwhere_" . rand(0, 9999);

		$values = [
			// Annotation values for entity 1
			[
				-3,
				0,
				5,
				8,
				'foo'
			],
			// Annotation values for entity 2
			[
				-8,
				-5,
				-2,
				0,
				1,
			],
			// Annotation values for entity 3
			[
				-4,
				-2,
				-1,
				'bar',
			]
		];

		$options = [
			'type' => 'object',
			'subtypes' => $subtypes,
			'limit' => $num_entities,
		];

		$entities = elgg_get_entities($options);

		$this->assertEqual($num_entities, count($entities));

		$guids = [];

		foreach ($entities as $index => $entity) {
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
				"CAST(n_table.value as DECIMAL(10, 2)) > 0"
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
				'where' => ["CAST(n_table.value AS DECIMAL(10, 2)) > 0"],
				'limit' => 0,
			]);

			if (count($assertion_values)) {
				$this->assertInternalType('array', $annotations);
				$this->assertEquals(count($assertion_values), count($annotations));
			} else {
				$this->assertFalse($annotations);
			}

			$annotation_values = [];
			foreach ($annotations as $ann) {
				$annotation_values[] = $ann->value;
			}

			$this->assertEqual(array_sum($assertion_values), array_sum($annotation_values));
			$this->assertEqual(array_sum($assertion_values), $e->getVolatileData('select:annotation_calculation'));
		}

		$options['count'] = true;

		$es_count = elgg_get_entities_from_annotation_calculation($options);

		// We have two entities with annotations values > 0
		$this->assertEqual(2, $es_count);
	}

	/**
	 * Get a count of entities using egefac()
	 * Testing to make sure that the count includes each entity with multiple annotations of the same name only once
	 * Irrespective of the calculation type passed
	 */
	public function testElggGetEntitiesFromAnnotationCalculationCount() {
		// add two annotations with a unique name to a set of entities
		// then count the number of entities using egefac()

		$es = $this->createMany('object', 3);
		$subtypes = array_map(function ($e) {
			return $e->getSubtype();
		}, $es);

		$name = 'test_annotation_' . rand(0, 9999);

		$options = [
			'type' => 'object',
			'subtypes' => $subtypes,
			'limit' => 3
		];

		$entities = elgg_get_entities($options);

		foreach ($entities as $entity) {
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
	 */
	public function testElggGetEntitiesFromAnnotationCalculationCountFromAnnotationValues() {

		$es = $this->createMany('object', 3);
		$subtypes = array_map(function ($e) {
			return $e->getSubtype();
		}, $es);

		$value = rand(0, 9999);

		$options = [
			'type' => 'object',
			'subtypes' => $subtypes,
			'limit' => 3
		];

		$es = elgg_get_entities($options);

		foreach ($es as $e) {
			$name = 'test_annotation_egefacval_' . rand(0, 9999);
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
	 */
	public function testElggGetEntitiesFromAnnotationCalculationCountFromAnnotationNameValuesPairs() {

		$es = $this->createMany('object', 3);
		$subtypes = array_map(function ($e) {
			return $e->getSubtype();
		}, $es);

		$value = rand(0, 9999);
		$name = 'test_annotation_egefacnv';

		$options = [
			'type' => 'object',
			'subtypes' => $subtypes,
			'limit' => 3
		];

		$es = elgg_get_entities($options);

		foreach ($es as $e) {
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

		$es = $this->createMany('object', 3);

		// create test annotations on a few entities.
		foreach ($es as $e) {
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

		$es = $this->createMany('object', 3);

		// create test annotations on a few entities.
		foreach ($es as $e) {
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

		$es = $this->createMany('object', 3);

		// create test annotations on a few entities.
		foreach ($es as $e) {
			$annotations = $this->createRandomAnnotations($e);

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
