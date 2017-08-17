<?php
/**
 * \Elgg\Queue\DatabaseQueue tests
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreDatabaseQueueTest extends \ElggCoreUnitTest {

	public function up() {

	}

	public function down() {

	}

	public function testEnqueueAndDequeue() {
		$queue = new \Elgg\Queue\DatabaseQueue('unit:test', _elgg_services()->db);
		$first = array(1, 2, 3);
		$second = array(4, 5, 6);

		$result = $queue->enqueue($first);
		$this->assertTrue($result);
		$result = $queue->enqueue($second);
		$this->assertTrue($result);

		$this->assertIdentical(2, $queue->size());

		$data = $queue->dequeue();
		$this->assertIdentical($first, $data);
		$data = $queue->dequeue();
		$this->assertIdentical($second, $data);

		$data = $queue->dequeue();
		$this->assertIdentical(null, $data);
	}

	public function testMultipleQueues() {
		$queue1 = new \Elgg\Queue\DatabaseQueue('unit:test1', _elgg_services()->db);
		$queue2 = new \Elgg\Queue\DatabaseQueue('unit:test2', _elgg_services()->db);
		$first = array(1, 2, 3);
		$second = array(4, 5, 6);

		$result = $queue1->enqueue($first);
		$this->assertTrue($result);
		$result = $queue2->enqueue($second);
		$this->assertTrue($result);

		$this->assertIdentical(1, $queue1->size());
		$this->assertIdentical(1, $queue2->size());

		$data = $queue2->dequeue();
		$this->assertIdentical($second, $data);
		$data = $queue1->dequeue();
		$this->assertIdentical($first, $data);
	}

	public function testClear() {
		$queue = new \Elgg\Queue\DatabaseQueue('unit:test',  _elgg_services()->db);
		$first = array(1, 2, 3);
		$second = array(4, 5, 6);

		$result = $queue->enqueue($first);
		$this->assertTrue($result);
		$result = $queue->enqueue($second);
		$this->assertTrue($result);

		$queue->clear();
		$data = $queue->dequeue();
		$this->assertIdentical(null, $data);
		$this->assertIdentical(0, $queue->size());
	}
}
