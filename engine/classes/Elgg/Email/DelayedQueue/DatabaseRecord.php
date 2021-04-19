<?php

namespace Elgg\Email\DelayedQueue;

use Elgg\Notifications\Notification;

/**
 * Record of the delayed_email_queue table
 *
 * @property-read int    $id                the record ID
 * @property-read int    $recipient_guid    the GUID of the recipient
 * @property-read string $delivery_interval the interval when to deliver the item
 * @property-read mixed  $data              the item to deliver
 * @property-read int    $timestamp         the timestamp when the record was inserted into the table
 *
 * @since 4.0
 * @internal
 */
class DatabaseRecord {
	
	/**
	 * @var \stdClass
	 */
	protected $row;
	
	/**
	 * Create a new record
	 *
	 * @param \stdClass $row the database record
	 */
	public function __construct(\stdClass $row) {
		$this->row = $row;
	}
	
	/**
	 * Get a row property
	 *
	 * @param string $name name of the value to get
	 *
	 * @return mixed
	 */
	public function __get($name) {
		
		switch ($name) {
			case 'id':
			case 'recipient_guid':
			case 'timestamp':
				return (int) $this->row->$name;
			
			case 'delivery_interval':
				return $this->row->$name;
			
			case 'data':
				return unserialize($this->row->data);
		}
		
		return null;
	}
	
	/**
	 * Remove the database row
	 *
	 * @return bool
	 */
	public function delete(): bool {
		return (bool) _elgg_services()->delayedEmailQueueTable->deleteRow($this->id);
	}
	
	/**
	 * Get the notification for this record
	 *
	 * @return null|Notification
	 */
	public function getNotification(): ?Notification {
		return $this->data instanceof Notification ? $this->data : null;
	}
}
