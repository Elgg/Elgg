<?php

namespace Elgg\Notifications;

use Elgg\IntegrationTestCase;
use ColdTrick\AdvancedNotifications\NotificationQueue;

class NotificationsQueueIntegrationTest extends IntegrationTestCase {

	/**
	 * @var NotificationQueue
	 */
	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->notificationsQueue;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		$this->service->clear();
	}
	
	public function testQueueDelay() {
		// set a high delay
		_elgg_services()->config->notifications_queue_delay = 100;
		// make sure the queue is empty
		$this->service->clear();
		
		$item = $this->createObject();
		
		$this->assertTrue($this->service->enqueue($item));
		
		// get the item before the delay is expired, should fail
		$this->assertEmpty($this->service->dequeue());
		
		// remove the queue delay, should return first item from the queue
		_elgg_services()->config->notifications_queue_delay = 0;
		
		$queue_item = $this->service->dequeue();
		$this->assertEquals(unserialize(serialize($item)), $queue_item);
	}
}
