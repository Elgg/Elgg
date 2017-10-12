<?php

use Elgg\BatchResult;

/**
 * test \ElggBatch
 *
 */
class ElggBatchTest extends \ElggCoreUnitTest {

	public function up() {

	}

	public function down() {

	}

	// see https://github.com/elgg/elgg/issues/4288
	public function testElggBatchIncOffset() {
		// normal increment
		$options = [
			'offset' => 0,
			'limit' => 11
		];
		$batch = new \ElggBatch([
			\ElggBatchTest::class,
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
		\ElggBatchTest::elgg_batch_callback_test([], true);
		$options = [
			'offset' => 0,
			'limit' => 11
		];
		$batch = new \ElggBatch([
			\ElggBatchTest::class,
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
		\ElggBatchTest::elgg_batch_callback_test([], true);
		$options = [
			'offset' => 3,
			'limit' => 11
		];
		$batch = new \ElggBatch([
			\ElggBatchTest::class,
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
			'type' => 'plugin',
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
