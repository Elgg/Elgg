<?php
/**
 * Elgg_Util_DatabaseQueue tests
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreDatabaseQueueTest extends ElggCoreUnitTest {

	public function testEnqueueAndDequeue() {
		$queue = new Elgg_Util_DatabaseQueue('unit:test');
		$first = array(1, 2, 3);
		$second = array(4, 5, 6);

		$result = $queue->enqueue($first);
		$this->assertTrue($result);
		$result = $queue->enqueue($second);
		$this->assertTrue($result);

		$data = $queue->dequeue();
		$this->assertIdentical($first, $data);
		$data = $queue->dequeue();
		$this->assertIdentical($second, $data);

		$data = $queue->dequeue();
		$this->assertIdentical(null, $data);
	}

	public function testMultipleQueues() {
		$queue1 = new Elgg_Util_DatabaseQueue('unit:test1');
		$queue2 = new Elgg_Util_DatabaseQueue('unit:test2');
		$first = array(1, 2, 3);
		$second = array(4, 5, 6);

		$result = $queue1->enqueue($first);
		$this->assertTrue($result);
		$result = $queue2->enqueue($second);
		$this->assertTrue($result);

		$data = $queue2->dequeue();
		$this->assertIdentical($second, $data);
		$data = $queue1->dequeue();
		$this->assertIdentical($first, $data);
	}

	public function testClear() {
		$queue = new Elgg_Util_DatabaseQueue('unit:test');
		$first = array(1, 2, 3);
		$second = array(4, 5, 6);

		$result = $queue->enqueue($first);
		$this->assertTrue($result);
		$result = $queue->enqueue($second);
		$this->assertTrue($result);

		$queue->clear();
		$data = $queue->dequeue();
		$this->assertIdentical(null, $data);
	}
}
