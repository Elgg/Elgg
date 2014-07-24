<?php

/**
 * test \ElggBatch
 *
 */
class ElggBatchTest extends \ElggCoreUnitTest {

	// see https://github.com/elgg/elgg/issues/4288
	public function testElggBatchIncOffset() {
		// normal increment
		$options = array(
			'offset' => 0,
			'limit' => 11
		);
		$batch = new \ElggBatch(array('\ElggBatchTest', 'elgg_batch_callback_test'), $options,
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
		\ElggBatchTest::elgg_batch_callback_test(array(), true);
		$options = array(
			'offset' => 0,
			'limit' => 11
		);
		$batch = new \ElggBatch(array('\ElggBatchTest', 'elgg_batch_callback_test'), $options,
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
		\ElggBatchTest::elgg_batch_callback_test(array(), true);
		$options = array(
			'offset' => 3,
			'limit' => 11
		);
		$batch = new \ElggBatch(array('\ElggBatchTest', 'elgg_batch_callback_test'), $options,
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
		$num_test_entities = 8;
		$guids = array();
		for ($i = $num_test_entities; $i > 0; $i--) {
			$entity = new \ElggObject();
			$entity->type = 'object';
			$entity->subtype = 'test_5357_subtype';
			$entity->access_id = ACCESS_PUBLIC;
			$entity->save();
			$guids[] = $entity->guid;
			_elgg_invalidate_cache_for_entity($entity->guid);
		}

		// break entities such that the first fetch has one incomplete
		// and the second and third fetches have only incompletes!
		$db_prefix = elgg_get_config('dbprefix');
		delete_data("
			DELETE FROM {$db_prefix}objects_entity
			WHERE guid IN ({$guids[1]}, {$guids[2]}, {$guids[3]}, {$guids[4]}, {$guids[5]})
		");

		$options = array(
			'type' => 'object',
			'subtype' => 'test_5357_subtype',
			'order_by' => 'e.guid',
		);

		$entities_visited = array();
		$batch = new \ElggBatch('elgg_get_entities', $options, null, 2);
		/* @var \ElggEntity[] $batch */
		foreach ($batch as $entity) {
			$entities_visited[] = $entity->guid;
		}

		// The broken entities should not have been visited
		$this->assertEqual($entities_visited, array($guids[0], $guids[6], $guids[7]));

		// cleanup (including leftovers from previous tests)
		$entity_rows = elgg_get_entities(array_merge($options, array(
			'callback' => '',
			'limit' => false,
		)));
		$guids = array();
		foreach ($entity_rows as $row) {
			$guids[] = $row->guid;
		}
		delete_data("DELETE FROM {$db_prefix}entities WHERE guid IN (" . implode(',', $guids) . ")");
		delete_data("DELETE FROM {$db_prefix}objects_entity WHERE guid IN (" . implode(',', $guids) . ")");
		remove_subtype('object', 'test_5357_subtype');
	}

	public function testElggBatchDeleteHandlesBrokenEntities() {
		$num_test_entities = 8;
		$guids = array();
		for ($i = $num_test_entities; $i > 0; $i--) {
			$entity = new \ElggObject();
			$entity->type = 'object';
			$entity->subtype = 'test_5357_subtype';
			$entity->access_id = ACCESS_PUBLIC;
			$entity->save();
			$guids[] = $entity->guid;
			_elgg_invalidate_cache_for_entity($entity->guid);
		}

		// break entities such that the first fetch has one incomplete
		// and the second and third fetches have only incompletes!
		$db_prefix = elgg_get_config('dbprefix');
		delete_data("
			DELETE FROM {$db_prefix}objects_entity
			WHERE guid IN ({$guids[1]}, {$guids[2]}, {$guids[3]}, {$guids[4]}, {$guids[5]})
		");

		$options = array(
			'type' => 'object',
			'subtype' => 'test_5357_subtype',
			'order_by' => 'e.guid',
		);

		$entities_visited = array();
		$batch = new \ElggBatch('elgg_get_entities', $options, null, 2, false);
		/* @var \ElggEntity[] $batch */
		foreach ($batch as $entity) {
			$entities_visited[] = $entity->guid;
			$entity->delete();
		}

		// The broken entities should not have been visited
		$this->assertEqual($entities_visited, array($guids[0], $guids[6], $guids[7]));

		// cleanup (including leftovers from previous tests)
		$entity_rows = elgg_get_entities(array_merge($options, array(
			'callback' => '',
			'limit' => false,
		)));
		$guids = array();
		foreach ($entity_rows as $row) {
			$guids[] = $row->guid;
		}
		delete_data("DELETE FROM {$db_prefix}entities WHERE guid IN (" . implode(',', $guids) . ")");
		delete_data("DELETE FROM {$db_prefix}objects_entity WHERE guid IN (" . implode(',', $guids) . ")");
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

		for ($j = 0; ($options['limit'] < 5) ? $j < $options['limit'] : $j < 5; $j++) {
			$return[] = array(
				'offset' => $options['offset'],
				'limit' => $options['limit'],
				'count' => $count++,
				'index' => 1 + $options['offset'] + $j
			);
		}

		return $return;
	}
}
