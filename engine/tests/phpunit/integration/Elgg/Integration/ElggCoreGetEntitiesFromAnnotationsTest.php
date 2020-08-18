<?php

namespace Elgg\Integration;

use Elgg\Database\Clauses\WhereClause;

/**
 * Test elgg_get_entities() with annotation options
 *
 * @group IntegrationTests
 * @group Entities
 * @group EntityAnnotations
 */
class ElggCoreGetEntitiesFromAnnotationsTest extends ElggCoreGetEntitiesBaseTest {

	/**
	 * {@inheritDoc}
	 * @see \Elgg\Integration\ElggCoreGetEntitiesBaseTest::down()
	 */
	public function down() {
		// cleanup test annotations
		$this->assertNotFalse(elgg_delete_annotations([
			'annotation_owner_guid' => $this->user->guid,
			'limit' => false,
		]));
		
		parent::down();
	}
	
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
			
			$id = $entity->annotate($name, $value, ACCESS_PRIVATE, $this->user->guid);
			$this->assertIsInt($id);
			
			$annotation = elgg_get_annotation_from_id($id);
			$this->assertInstanceOf(\ElggAnnotation::class, $annotation);
			
			$annotations[] = $annotation;
		}

		return $annotations;
	}

	public function testCanGetEntitiesByAnnotationCreationTime() {

		$user1 = $this->createOne('user');

		$prefix = _elgg_services()->config->dbprefix;

		$annotation_name = 'test_annotation_name_' . rand();

		// our targets
		$valid1 = $this->createOne();
		$id1 = $valid1->annotate($annotation_name, 1, ACCESS_PUBLIC, $user1->guid);

		// this one earlier
		$yesterday = time() - 86400;
		elgg()->db->updateData("
			UPDATE {$prefix}annotations
			SET time_created = $yesterday
			WHERE id = $id1
		");

		$valid2 = $this->createOne();
		$valid2->annotate($annotation_name, 1, ACCESS_PUBLIC, $user1->guid);

		$entities = elgg_get_entities([
			'annotation_owner_guid' => $user1->guid,
			'annotation_created_time_lower' => (time() - 3600),
			'annotation_name' => $annotation_name,
		]);

		$this->assertCount(1, $entities);
		$this->assertEquals($valid2->guid, $entities[0]->guid);

		$entities = elgg_get_entities([
			'annotation_owner_guid' => $user1->guid,
			'annotation_created_time_upper' => (time() - 3600),
			'annotation_name' => $annotation_name,
		]);

		$this->assertCount(1, $entities);
		$this->assertEquals($valid1->guid, $entities[0]->guid);
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

		$guids[] = $valid->guid;
		$valid->annotate($annotation_name, $annotation_value, ACCESS_PRIVATE, $user1->guid);

		$valid2 = $this->createOne();
		$guids[] = $valid2->guid;
		$valid2->annotate($annotation_name2, $annotation_value2, ACCESS_PRIVATE, $user2->guid);

		$entities = elgg_get_entities([
			'annotation_owner_guid' => $user1->guid,
			'annotation_name' => $annotation_name,
		]);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $guids));
			$annotations = $entity->getAnnotations([
				'annotation_name' => $annotation_name,
			]);
			$this->assertCount(1, $annotations);

			$this->assertEquals($annotation_name, $annotations[0]->name);
			$this->assertEquals($annotation_value, $annotations[0]->value);
			$this->assertEquals($user1->guid, $annotations[0]->owner_guid);
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
		$valid->annotate($annotation_name, $annotation_value, ACCESS_PRIVATE, $user1->guid);

		$valid2 = $this->createOne();
		$guids[] = $valid2->getGUID();
		$valid2->annotate($annotation_name2, $annotation_value2, ACCESS_PRIVATE, $user2->guid);

		$entities = elgg_get_entities([
			'annotation_owner_guid' => $user1->guid,
			'annotation_name' => $annotation_name,
			'selects' => ['MAX(n_table.time_created) AS maxtime'],
			'group_by' => 'n_table.entity_guid',
			'order_by' => 'maxtime',
		]);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $guids));
			$annotations = $entity->getAnnotations([
				'annotation_name' => $annotation_name,
			]);
			$this->assertCount(1, $annotations);

			$this->assertEquals($annotation_name, $annotations[0]->name);
			$this->assertEquals($annotation_value, $annotations[0]->value);
			$this->assertEquals($user1->guid, $annotations[0]->owner_guid);
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

		$expected_values = [];
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

			$expected_values[$e->guid] = $calc_value;
		}

		arsort($expected_values);

		$expected_order = array_keys($expected_values);

		$options = [
			'type' => 'object',
			'guids' => array_keys($expected_values),
			'annotation_name' => $name,
			'annotation_sort_by_calculation' => $type,
		];

		$es = elgg_get_entities($options);

		$actual_order = array_map(function ($e) {
			return $e->guid;
		}, $es);

		foreach ($es as $e) {
			$value = 0;
			$as = $e->getAnnotations([
				'annotation_name' => $name,
			]);
			
			// should only ever be 2
			$this->assertCount(2, $as);

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

			$this->assertEquals($expected_values[$e->guid], $calc_value);
			// casting to float because of different precision between PHP and DB
			$this->assertEquals($calc_value, (float) $e->getVolatileData('select:annotation_calculation'));
		}

		$this->assertEquals($expected_order, $actual_order);

		unset($options['annotation_sort_by_calculation']);
		$es_count = elgg_count_entities($options);
		$this->assertEquals($num_entities, $es_count);
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

		$this->assertCount($num_entities, $entities);

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
			'annotation_sort_by_calculation' => 'sum',
			'wheres' => [
				new WhereClause("CAST(n_table.value as DECIMAL(10, 2)) > 0"),
			],
		];

		$es = elgg_get_entities($options);

		foreach ($es as $i => $e) {

			$assertion_values = [];

			foreach ($values[$i] as $value) {
				if (is_numeric($value) && $value > 0) {
					$assertion_values[] = $value;
				}
			}

			$annotations = $e->getAnnotations([
				'annotation_name' => $name,
				'where' => [
					new WhereClause("CAST(n_table.value AS DECIMAL(10, 2)) > 0"),
				],
				'limit' => 0,
			]);

			$this->assertIsArray($annotations);
			$this->assertEquals(count($assertion_values), count($annotations));
			
			$annotation_values = [];
			foreach ($annotations as $ann) {
				$annotation_values[] = $ann->value;
			}

			$this->assertEquals(array_sum($assertion_values), array_sum($annotation_values));
			$this->assertEquals(array_sum($assertion_values), $e->getVolatileData('select:annotation_calculation'));
		}

		unset($options['annotation_sort_by_calculation']);
		$es_count = elgg_count_entities($options);

		// We have two entities with annotations values > 0
		$this->assertEquals(2, $es_count);
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

		$this->assertEquals(count($a_e_map), count($as));

		foreach ($as as $a) {
			$this->assertEquals($a_e_map[$a->id], $a->entity_guid);
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

		$this->assertEquals(count($a_e_map), count($as));

		foreach ($as as $a) {
			$this->assertEquals($a_e_map[$a->id], $a->entity_guid);
		}
	}

	public function testElggGetAnnotationsAnnotationOwnerGuids() {
		$options = ['annotation_owner_guids' => []];
		$a_e_map = [];

		$es = $this->createMany('object', 3);

		// create test annotations on a few entities.
		foreach ($es as $e) {
			// remove annotations left over from previous tests.
			elgg_delete_annotations([
				'annotation_entity_guid' => $e->guid,
			]);
			$annotations = $this->createRandomAnnotations($e);

			foreach ($annotations as $a) {
				$options['annotation_owner_guids'][] = $a->owner_guid;
				$a_e_map[$a->id] = $e->guid;
			}
		}

		$as = elgg_get_annotations($options);
		$this->assertEquals(count($a_e_map), count($as));

		foreach ($as as $a) {
			$this->assertEquals($a_e_map[$a->id], $a->entity_guid);
		}
	}
}
