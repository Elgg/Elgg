<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;
use Elgg\Queue\DatabaseQueue;

/**
 * \Elgg\Queue\DatabaseQueue tests
 *
 * @group IntegrationTests
 */
class ElggCoreDatabaseQueueTest extends IntegrationTestCase {

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

		$this->assertEquals(2, $queue->size());

		$data = $queue->dequeue();
		$this->assertEquals($first, $data);
		$data = $queue->dequeue();
		$this->assertEquals($second, $data);

		$data = $queue->dequeue();
		$this->assertNull($data);
	}

	public function testMultipleQueues() {
		$queue1 = new DatabaseQueue('unit:test1', _elgg_services()->db);
		$queue2 = new DatabaseQueue('unit:test2', _elgg_services()->db);
		$first = array(1, 2, 3);
		$second = array(4, 5, 6);

		$result = $queue1->enqueue($first);
		$this->assertTrue($result);
		$result = $queue2->enqueue($second);
		$this->assertTrue($result);

		$this->assertEquals(1, $queue1->size());
		$this->assertEquals(1, $queue2->size());

		$data = $queue2->dequeue();
		$this->assertEquals($second, $data);
		$data = $queue1->dequeue();
		$this->assertEquals($first, $data);
	}

	public function testClear() {
		$queue = new DatabaseQueue('unit:test',  _elgg_services()->db);
		$first = array(1, 2, 3);
		$second = array(4, 5, 6);

		$result = $queue->enqueue($first);
		$this->assertTrue($result);
		$result = $queue->enqueue($second);
		$this->assertTrue($result);

		$queue->clear();
		$data = $queue->dequeue();
		$this->assertNull($data);
		$this->assertEquals(0, $queue->size());
	}
}
