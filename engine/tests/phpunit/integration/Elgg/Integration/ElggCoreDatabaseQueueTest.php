<?php

namespace Elgg\Integration;

use Elgg\LegacyIntegrationTestCase;
use Elgg\Queue\DatabaseQueue;

/**
 * \Elgg\Queue\DatabaseQueue tests
 *
 * @group IntegrationTests
 */
class ElggCoreDatabaseQueueTest extends LegacyIntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testEnqueueAndDequeue() {
		$queue = new DatabaseQueue('unit:test', _elgg_services()->db);
		$first = array(1, 2, 3);
		$second = array(4, 5, 6);

		$result = $queue->enqueue($first);
		$this->assertTrue((bool) $result);
		$result = $queue->enqueue($second);
		$this->assertTrue((bool) $result);

		$this->assertIdentical(2, $queue->size());

		$data = $queue->dequeue();
		$this->assertIdentical($first, $data);
		$data = $queue->dequeue();
		$this->assertIdentical($second, $data);

		$data = $queue->dequeue();
		$this->assertIdentical(null, $data);
	}

	public function testMultipleQueues() {
		$queue1 = new DatabaseQueue('unit:test1', _elgg_services()->db);
		$queue2 = new DatabaseQueue('unit:test2', _elgg_services()->db);
		$first = array(1, 2, 3);
		$second = array(4, 5, 6);

		$result = $queue1->enqueue($first);
		$this->assertTrue((bool) $result);
		$result = $queue2->enqueue($second);
		$this->assertTrue((bool) $result);

		$this->assertIdentical(1, $queue1->size());
		$this->assertIdentical(1, $queue2->size());

		$data = $queue2->dequeue();
		$this->assertIdentical($second, $data);
		$data = $queue1->dequeue();
		$this->assertIdentical($first, $data);
	}

	public function testClear() {
		$queue = new DatabaseQueue('unit:test',  _elgg_services()->db);
		$first = array(1, 2, 3);
		$second = array(4, 5, 6);

		$result = $queue->enqueue($first);
		$this->assertTrue((bool) $result);
		$result = $queue->enqueue($second);
		$this->assertTrue((bool) $result);

		$queue->clear();
		$data = $queue->dequeue();
		$this->assertIdentical(null, $data);
		$this->assertIdentical(0, $queue->size());
	}
}
