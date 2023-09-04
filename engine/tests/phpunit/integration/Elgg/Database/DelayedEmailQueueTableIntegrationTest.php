<?php

namespace Elgg\Database;

use Elgg\Email\DelayedQueue\DatabaseRecord;
use Elgg\IntegrationTestCase;
use Elgg\Notifications\Notification;
use Elgg\Values;

class DelayedEmailQueueTableIntegrationTest extends IntegrationTestCase {

	/**
	 * @var DelayedEmailQueueTable
	 */
	protected $table;

	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->table = _elgg_services()->delayedEmailQueueTable;
	}
	
	/**
	 * Create test notification
	 *
	 * @return Notification
	 */
	protected function getTestNotification(): Notification {
		$recipient = $this->createUser();
		$sender = $this->createUser();
		
		return new Notification($sender, $recipient, 'en', 'Test subject', 'Test body');
	}
	
	public function testQueueEmail() {
		$notification = $this->getTestNotification();
		$recipient = $notification->getRecipient();
		
		// insert
		$this->assertTrue($this->table->queueEmail($recipient->guid, 'daily', $notification));
		
		$dt = $this->table->getCurrentTime('-10 seconds');
		
		// retrieve before inserted time
		$this->assertEmpty($this->table->getRecipientRows($recipient->guid, 'daily', $dt->getTimestamp()));
		
		$dt = $this->table->getCurrentTime('+10 seconds');
		
		// retrieve after inserted time
		$rows = $this->table->getRecipientRows($recipient->guid, 'daily', $dt->getTimestamp());
		$this->assertCount(1, $rows);
		$this->assertEmpty($this->table->getRecipientRows($recipient->guid, 'weekly', $dt->getTimestamp()));
		
		$row = $rows[0];
		$this->assertInstanceOf(DatabaseRecord::class, $row);
		
		$this->assertEquals($row, $this->table->getRow($row->id));
		
		// validate row contents
		$this->assertEquals($recipient->guid, $row->recipient_guid);
		$this->assertEquals('daily', $row->delivery_interval);
		$this->assertEquals(unserialize(serialize($notification)), $row->getNotification());
		
		// delete
		$this->assertTrue($row->delete());
		
		$this->assertEmpty($this->table->getRecipientRows($recipient->guid, 'daily', $dt->getTimestamp()));
	}
	
	public function testGetRecipientRowsWithMaxResults() {
		$notification = $this->getTestNotification();
		$recipient = $notification->getRecipient();
		
		// insert
		for ($i = 0; $i < 5; $i++) {
			$this->assertTrue($this->table->queueEmail($recipient->guid, 'daily', $notification));
		}
		
		$dt = $this->table->getCurrentTime('+10 seconds');
		
		$rows = $this->table->getRecipientRows($recipient->guid, 'daily', $dt->getTimestamp(), 2);
		$this->assertNotEmpty($rows);
		$this->assertCount(2, $rows);
		
		$same_rows = $this->table->getRecipientRows($recipient->guid, 'daily', $dt->getTimestamp(), 2);
		$this->assertNotEmpty($same_rows);
		$this->assertCount(2, $same_rows);
		$this->assertEquals($rows, $same_rows);
		
		// fetch too much
		$rows = $this->table->getRecipientRows($recipient->guid, 'daily', $dt->getTimestamp(), 100);
		$this->assertNotEmpty($rows);
		$this->assertCount(5, $rows);
	}
	
	public function testGetNextRecipientGUID() {
		$recipients = [];
		
		// insert rows in reverse order
		for ($i = 0; $i < 5; $i++) {
			$notification = $this->getTestNotification();
			$recipient = $notification->getRecipient();
			$recipients[] = $recipient->guid;
			
			$this->table->setCurrentTime(Values::normalizeTime("-{$i} minutes"));
			$this->table->queueEmail($recipient->guid, 'daily', $notification);
		}
		
		$this->table->resetCurrentTime();
		
		$next_recipient = $this->table->getNextRecipientGUID('daily');
		$this->assertNotEmpty($next_recipient);
		$this->assertEquals(end($recipients), $next_recipient);
	}
	
	public function testDeleteRecipientRows() {
		$notification = $this->getTestNotification();
		$recipient = $notification->getRecipient();
		
		// insert
		for ($i = 0; $i < 5; $i++) {
			$this->assertTrue($this->table->queueEmail($recipient->guid, 'daily', $notification));
			$this->assertTrue($this->table->queueEmail($recipient->guid, 'weekly', $notification));
		}
		
		$dt = $this->table->getCurrentTime('+10 seconds');
		
		// delete
		$this->assertEquals(5, $this->table->deleteRecipientRows($recipient->guid, 'daily', $dt->getTimestamp()));
		$this->assertEquals(5, $this->table->deleteRecipientRows($recipient->guid, 'weekly', $dt->getTimestamp()));
		
		// verify
		$this->assertEmpty($this->table->getRecipientRows($recipient->guid, 'daily', $dt->getTimestamp()));
		$this->assertEmpty($this->table->getRecipientRows($recipient->guid, 'weekly', $dt->getTimestamp()));
	}
	
	public function testDeleteRecipientRowsWithMaxID() {
		$notification = $this->getTestNotification();
		$recipient = $notification->getRecipient();
		
		// insert
		for ($i = 0; $i < 5; $i++) {
			$this->assertTrue($this->table->queueEmail($recipient->guid, 'daily', $notification));
		}
		
		$dt = $this->table->getCurrentTime('+10 seconds');
		
		$rows = $this->table->getRecipientRows($recipient->guid, 'daily', $dt->getTimestamp(), 2);
		$max_id = 0;
		foreach ($rows as $row) {
			$max_id = max($max_id, $row->id);
		}
		
		// delete
		$this->assertEquals(2, $this->table->deleteRecipientRows($recipient->guid, 'daily', $dt->getTimestamp(), $max_id));
		
		// verify still rows left
		$rows = $this->table->getRecipientRows($recipient->guid, 'daily', $dt->getTimestamp());
		$this->assertNotEmpty($rows);
		$this->assertCount(3, $rows);
		
		// delete the rest
		$this->assertEquals(3, $this->table->deleteRecipientRows($recipient->guid, 'daily', $dt->getTimestamp()));
		
		// verify all is now removed
		$this->assertEmpty($this->table->getRecipientRows($recipient->guid, 'daily', $dt->getTimestamp()));
	}
	
	public function testDeleteAllRecipientRows() {
		$notification = $this->getTestNotification();
		$recipient = $notification->getRecipient();
		
		// insert
		for ($i = 0; $i < 5; $i++) {
			$this->assertTrue($this->table->queueEmail($recipient->guid, 'daily', $notification));
			$this->assertTrue($this->table->queueEmail($recipient->guid, 'weekly', $notification));
		}
		
		$dt = $this->table->getCurrentTime('+10 seconds');
		
		// delete
		$this->assertEquals(10, $this->table->deleteAllRecipientRows($recipient->guid));
		
		// verify
		$this->assertEmpty($this->table->getRecipientRows($recipient->guid, 'daily', $dt->getTimestamp()));
		$this->assertEmpty($this->table->getRecipientRows($recipient->guid, 'weekly', $dt->getTimestamp()));
	}
	
	public function testRecipientRowsDeletedOnRecipientDelete() {
		$recipient = $this->createUser();
		$recipient_guid = $recipient->guid;
		
		// insert
		for ($i = 0; $i < 5; $i++) {
			$this->assertTrue($this->table->queueEmail($recipient->guid, 'daily', []));
			$this->assertTrue($this->table->queueEmail($recipient->guid, 'weekly', []));
		}
				
		// delete
		$this->assertTrue(elgg_call(ELGG_IGNORE_ACCESS, function() use ($recipient) {
			return $recipient->delete();
		}));
		
		// verify
		$dt = $this->table->getCurrentTime('+10 seconds');
		$this->assertEmpty($this->table->getRecipientRows($recipient_guid, 'daily', $dt->getTimestamp()));
		$this->assertEmpty($this->table->getRecipientRows($recipient_guid, 'weekly', $dt->getTimestamp()));
	}
	
	public function testUpdateRecipientInterval() {
		$notification = $this->getTestNotification();
		$recipient = $notification->getRecipient();
		
		// insert
		for ($i = 0; $i < 5; $i++) {
			$this->assertTrue($this->table->queueEmail($recipient->guid, 'daily', $notification));
		}
		
		// different interval
		for ($i = 0; $i < 5; $i++) {
			$this->assertTrue($this->table->queueEmail($recipient->guid, 'weekly', $notification));
		}
		
		$dt = $this->table->getCurrentTime('+10 seconds');
		
		// update
		$this->assertTrue($this->table->updateRecipientInterval($recipient->guid, 'weekly'));
		
		// verify
		$this->assertEmpty($this->table->getRecipientRows($recipient->guid, 'daily', $dt->getTimestamp()));
		$this->assertCount(10, $this->table->getRecipientRows($recipient->guid, 'weekly', $dt->getTimestamp()));
	}
}
