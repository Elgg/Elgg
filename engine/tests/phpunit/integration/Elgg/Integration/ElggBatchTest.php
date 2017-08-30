<?php

namespace Elgg\Integration;

use Elgg\BatchResult;
use Elgg\LegacyIntegrationTestCase;
use ElggBatch;
use ElggObject;

/**
 * @group IntegrationTests
 * @group ElggBatch
 */
class ElggBatchTest extends LegacyIntegrationTestCase {

	/**
	 * @var array
	 */
	private $guids;

	public function up() {
		$entities = $this->createMany(['object'], 8);
		$this->guids = array_map(function($e) {
			return $e->guid;
		}, $entities);
	}

	public function down() {

	}

	// see https://github.com/elgg/elgg/issues/4288
	public function testElggBatchIncOffset() {
		// normal increment
		$options = [
			'guids' => $this->guids,
			'offset' => 0,
			'limit' => 11
		];
		$batch = new ElggBatch([
			ElggBatchTest::class,
			'elgg_batch_callback_test'
		], $options,
			null, 5);
		$j = 0;
		foreach ($batch as $e) {
			$offset = floor($j / 5) * 5;
			$this->assertEqual($offset, $e['offset']);
			$this->assertEqual($j + 1, $e['index']);
			$j++;
		}

		$this->assertEqual(11, $j);

		// no increment, 0 start
		ElggBatchTest::elgg_batch_callback_test([], true);
		$options = [
			'guids' => $this->guids,
			'offset' => 0,
			'limit' => 11
		];
		$batch = new ElggBatch([
			ElggBatchTest::class,
			'elgg_batch_callback_test'
		], $options,
			null, 5);
		$batch->setIncrementOffset(false);

		$j = 0;
		foreach ($batch as $e) {
			$this->assertEqual(0, $e['offset']);
			// should always be the same 5
			$this->assertEqual($e['index'], $j + 1 - (floor($j / 5) * 5));
			$j++;
		}
		$this->assertEqual(11, $j);

		// no increment, 3 start
		ElggBatchTest::elgg_batch_callback_test([], true);
		$options = [
			'guids' => $this->guids,
			'offset' => 3,
			'limit' => 11
		];
		$batch = new ElggBatch([
			ElggBatchTest::class,
			'elgg_batch_callback_test'
		], $options,
			null, 5);
		$batch->setIncrementOffset(false);

		$j = 0;
		foreach ($batch as $e) {
			$this->assertEqual(3, $e['offset']);
			// same 5 results
			$this->assertEqual($e['index'], $j + 4 - (floor($j / 5) * 5));
			$j++;
		}

		$this->assertEqual(11, $j);
	}

	public function testElggBatchReadHandlesBrokenEntities() {
		elgg_get_session()->entityCache->clear();

		$guids = $this->guids;

		// break entities such that the first fetch has one incomplete
		// and the second and third fetches have only incompletes!
		$db_prefix = _elgg_config()->dbprefix;
		delete_data("
			DELETE FROM {$db_prefix}objects_entity
			WHERE guid IN ({$guids[1]}, {$guids[2]}, {$guids[3]}, {$guids[4]}, {$guids[5]})
		");

		$options = [
			'guids' => $this->guids,
			'type' => 'object',
			'order_by' => 'e.guid',
		];

		$entities_visited = [];
		$batch = new ElggBatch('elgg_get_entities', $options, null, 2);
		/* @var \ElggEntity[] $batch */
		foreach ($batch as $entity) {
			$entities_visited[] = $entity->guid;
		}

		// The broken entities should not have been visited
		$this->assertEqual([
			$guids[0],
			$guids[6],
			$guids[7]
		], $entities_visited);

		// cleanup (including leftovers from previous tests)
		$entity_rows = elgg_get_entities(array_merge($options, [
			'guids' => $this->guids,
			'callback' => '',
			'limit' => false,
		]));
		$guids = [];
		foreach ($entity_rows as $row) {
			$guids[] = $row->guid;
		}
		delete_data("DELETE FROM {$db_prefix}entities WHERE guid IN (" . implode(',', $guids) . ")");
		delete_data("DELETE FROM {$db_prefix}objects_entity WHERE guid IN (" . implode(',', $guids) . ")");
	}

	public function testElggBatchDeleteHandlesBrokenEntities() {

		elgg_get_session()->entityCache->clear();

		$guids = $this->guids;

		// break entities such that the first fetch has one incomplete
		// and the second and third fetches have only incompletes!
		$db_prefix = _elgg_config()->dbprefix;
		delete_data("
			DELETE FROM {$db_prefix}objects_entity
			WHERE guid IN ({$guids[1]}, {$guids[2]}, {$guids[3]}, {$guids[4]}, {$guids[5]})
		");

		$options = [
			'guids' => $this->guids,
			'type' => 'object',
			'order_by' => 'e.guid',
		];

		$entities_visited = [];
		$batch = new ElggBatch('elgg_get_entities', $options, null, 2, false);
		/* @var \ElggEntity[] $batch */
		foreach ($batch as $entity) {
			$entities_visited[] = $entity->guid;
			$entity->delete();
		}

		// The broken entities should not have been visited
		$this->assertEqual([
			$guids[0],
			$guids[6],
			$guids[7]
		], $entities_visited);

		// cleanup (including leftovers from previous tests)
		$entity_rows = elgg_get_entities(array_merge($options, [
			'guids' => $this->guids,
			'callback' => '',
			'limit' => false,
		]));
		$guids = [];
		foreach ($entity_rows as $row) {
			$guids[] = $row->guid;
		}
		delete_data("DELETE FROM {$db_prefix}entities WHERE guid IN (" . implode(',', $guids) . ")");
		delete_data("DELETE FROM {$db_prefix}objects_entity WHERE guid IN (" . implode(',', $guids) . ")");
	}

	public function testBatchCanCount() {
		$getter = function ($options) {
			if ($options['count']) {
				return 20;
			}

			return false;
		};
		$options = [
			// Due to 10992, if count was present and false, it would fail
			'count' => false,
		];

		$count1 = count(new ElggBatch($getter, $options));
		$count2 = $getter(array_merge($options, ['count' => true]));

		$this->assertEqual($count1, $count2);
	}

	public function testCanGetBatchFromAnEntityGetter() {

		$options = [
			'guids' => $this->guids,
			'type' => 'object',
			'limit' => 5,
			'callback' => function ($row) {
				return $row->guid;
			},
		];

		$guids1 = elgg_get_entities($options);

		$batch = elgg_get_entities(array_merge($options, ['batch' => true]));

		$this->assertIsA($batch, BatchResult::class);
		/* @var ElggBatch $batch */

		$guids2 = [];
		foreach ($batch as $val) {
			$guids2[] = $val;
		}

		$this->assertEqual($guids1, $guids2);
	}

	public static function elgg_batch_callback_test($options, $reset = false) {
		static $count = 1;

		if ($reset) {
			$count = 1;

			return true;
		}

		if ($count > 20) {
			return false;
		}

		$return = [];

		for ($j = 0; ($options['limit'] < 5) ? $j < $options['limit'] : $j < 5; $j++) {
			$return[] = [
				'offset' => $options['offset'],
				'limit' => $options['limit'],
				'count' => $count++,
				'index' => 1 + $options['offset'] + $j
			];
		}

		return $return;
	}
}
