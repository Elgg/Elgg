<?php

namespace Elgg\Notifications;

use Elgg\UnitTestCase;

class NotificationUnitTest extends UnitTestCase {

	/**
	 * @var \ElggEntity[]
	 */
	protected $entities = [];
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		foreach ($this->entities as $entity) {
			$entity->delete();
		}
	}
	
	/**
	 * Get a testing notification
	 *
	 * @return Notification
	 */
	protected function getNotification(): Notification {
		$this->entities['from'] = $from = $this->createUser();
		$this->entities['to'] = $to = $this->createUser();
		$this->entities['object'] = $object = $this->createObject([
			'owner_guid' => $from->guid,
		]);
		
		$params = [
			'subject' => 'Test subject',
			'body' => 'Test body',
			'summary' => 'Test summary',
			'url' => 'https://example.com/',
			'event' => new SubscriptionNotificationEvent($object, 'create', $from),
		];
		
		return new Notification($from, $to, $to->getLanguage(), 'Test subject', 'Test body', 'Test summary', $params);
	}
	
	public function testGetSender() {
		$notification = $this->getNotification();
		$sender = $this->entities['from'];
		
		$this->assertEquals($sender, $notification->getSender());
		$this->assertEquals($sender->guid, $notification->getSenderGUID());
	}
	
	public function testGetRecipient() {
		$notification = $this->getNotification();
		$recipient = $this->entities['to'];
		
		$this->assertEquals($recipient, $notification->getRecipient());
		$this->assertEquals($recipient->guid, $notification->getRecipientGUID());
	}
	
	public function testPublicClassProperties() {
		$notification = $this->getNotification();
		
		$this->assertEquals('Test subject', $notification->subject);
		$this->assertEquals('Test body', $notification->body);
		$this->assertEquals('Test summary', $notification->summary);
		$this->assertEquals('https://example.com/', $notification->url);
		$this->assertEquals($this->entities['to']->getlanguage(), $notification->language);
		
		$params = [
			'subject' => 'Test subject',
			'body' => 'Test body',
			'summary' => 'Test summary',
			'url' => 'https://example.com/',
			'event' => new SubscriptionNotificationEvent($this->entities['object'], 'create', $this->entities['from']),
		];
		$this->assertEquals($params, $notification->params);
	}
	
	public function testSerialization() {
		$notification = $this->getNotification();
		$serialized = serialize($notification);
		
		$this->assertNotEmpty($serialized);
		$this->assertIsString($serialized);
		
		$unserialized = unserialize($serialized);
		$this->assertInstanceOf(Notification::class, $unserialized);
		$this->assertEquals($notification->getSenderGUID(), $unserialized->getSenderGUID());
		$this->assertEquals($notification->getRecipientGUID(), $unserialized->getRecipientGUID());
		
		$this->assertEquals($notification->subject, $unserialized->subject);
		$this->assertEquals($notification->body, $unserialized->body);
		$this->assertEquals($notification->summary, $unserialized->summary);
		$this->assertEquals($notification->url, $unserialized->url);
		$this->assertEquals($notification->language, $unserialized->language);
		
		// checking event separately because serialization removes temp/cached entity values
		$this->assertInstanceOf(SubscriptionNotificationEvent::class, $unserialized->params['event']);
		unset($notification->params['event']);
		unset($unserialized->params['event']);
		
		$this->assertEquals($notification->params, $unserialized->params);
	}
}
