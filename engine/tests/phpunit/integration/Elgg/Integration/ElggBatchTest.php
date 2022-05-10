<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;
use ElggBatch;

/**
 * @group IntegrationTests
 */
class ElggBatchTest extends IntegrationTestCase {

	// see https://github.com/elgg/elgg/issues/4288
	public function testElggBatchIncOffset() {
		// normal increment
		$options = [
			'offset' => 0,
			'limit' => 11,
		];
		$batch = new ElggBatch([
			ElggBatchTest::class,
			'elgg_batch_callback_test'
		], $options,
			null, 5);
		$j = 0;
		foreach ($batch as $e) {
			$offset = (int) floor($j / 5) * 5;
			$this->assertEquals($offset, $e['offset']);
			$this->assertEquals($j + 1, $e['index']);
			$j++;
		}

		$this->assertEquals(11, $j);

		// no increment, 0 start
		ElggBatchTest::elgg_batch_callback_test([], true);
		$options = [
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
			$this->assertEquals(0, $e['offset']);
			// should always be the same 5
			$this->assertEquals($e['index'], $j + 1 - (floor($j / 5) * 5));
			$j++;
		}
		$this->assertEquals(11, $j);

		// no increment, 3 start
		ElggBatchTest::elgg_batch_callback_test([], true);
		$options = [
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
			$this->assertEquals(3, $e['offset']);
			// same 5 results
			$this->assertEquals($e['index'], $j + 4 - (floor($j / 5) * 5));
			$j++;
		}

		$this->assertEquals(11, $j);
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

		$this->assertEquals($count1, $count2);
	}

	public function testCanGetBatchFromAnEntityGetter() {

		$subtype ='testCanGetBatchFromAnEntityGetter';
		for ($i = 1; $i <= 5; $i++) {
			$this->createObject([
				'subtype' => $subtype,
			]);
		}

		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'limit' => 5,
			'callback' => function ($row) {
				return $row->guid;
			},
		];

		$guids1 = elgg_get_entities($options);

		$batch = elgg_get_entities(array_merge($options, ['batch' => true]));

		$this->assertInstanceOf(\ElggBatch::class, $batch);
		/* @var ElggBatch $batch */

		$guids2 = [];
		foreach ($batch as $val) {
			$guids2[] = $val;
		}

		$this->assertEquals($guids1, $guids2);
	}
	
	public function testReportFailure() {
		$time = time();
		$subtype = 'elggBatchReportFailureSubtype';
		$owner = $this->getRandomUser();
		elgg_get_session()->setLoggedInUser($owner);
		
		for ($i = 0; $i < 5; $i++) {
			$this->createObject([
				'owner_guid' => $owner->guid,
				'subtype' => $subtype,
			]);
		}
		
		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'created_after' => $time, // needed if previous tests failed
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
			'batch_size' => 2,
		];
		
		$count = elgg_count_entities($options);
		$this->assertEquals(5, $count);
		
		/* @var $batch \ElggBatch */
		$batch = elgg_get_entities($options);
		$this->assertInstanceOf('\ElggBatch', $batch);
		
		/* @var $entity \ElggObject */
		foreach ($batch as $index => $entity) {
			if ($index < 2) {
				$batch->reportFailure();
				continue;
			}
			
			$entity->delete();
			
			if ($index > 10) {
				// just in case
				break;
			}
		}
		
		$count = elgg_count_entities($options);
		$this->assertEquals(2, $count);
	}
	
	public function testQueryCacheDisabledButNotCleared() {
		$time = time();
		$subtype = 'queryCacheTest';
		$owner = $this->getRandomUser();
		elgg_get_session()->setLoggedInUser($owner);
		
		for ($i = 0; $i < 5; $i++) {
			$this->createObject([
				'owner_guid' => $owner->guid,
				'subtype' => $subtype,
			]);
		}
		
		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'created_after' => $time, // needed if previous tests failed
			'limit' => false,
			'batch' => true,
			'batch_size' => 2,
		];
		
		$count = elgg_count_entities($options);
		$this->assertEquals(5, $count);
		
		$queryCache = _elgg_services()->queryCache;
		$this->assertTrue($queryCache->isEnabled());
		$queryCache->set('foo', 'bar');
		$this->assertEquals('bar', $queryCache->get('foo'));
		
		$cache_size = $queryCache->size();
		
		/* @var $batch \ElggBatch */
		$batch = elgg_get_entities($options);
		$this->assertInstanceOf('\ElggBatch', $batch);
		
		/* @var $entity \ElggObject */
		foreach ($batch as $entity) {
			// do nothing, just loop
			// the entities query shouldn't end up in the cache
		}
		
		$this->assertTrue($queryCache->isEnabled());
		$this->assertEquals('bar', $queryCache->get('foo'));
		$this->assertEquals($cache_size, $queryCache->size());
		
		// do a normal elgg_get_entities()
		// this should increate the cache size
		$cache_size = $queryCache->size();
		$options['batch'] = false;
		$entities = elgg_get_entities($options);
		$this->assertIsArray($entities);
		$this->assertCount(5, $entities);
		
		$this->assertGreaterThan($cache_size, $queryCache->size());
		
		$queryCache->clear();
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
