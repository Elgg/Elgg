<?php

namespace Elgg\Database;

use Elgg\Database;
use Elgg\Email\DelayedQueue\DatabaseRecord;
use Elgg\Traits\TimeUsing;

/**
 * Interfaces with the database to perform operations on the delayed_email_queue table
 *
 * @internal
 * @since 4.0
 */
class DelayedEmailQueueTable {
	
	use TimeUsing;
	
	/**
	 * @var string name of the database table
	 */
	const TABLE_NAME = 'delayed_email_queue';
	
	/**
	 * @var Database
	 */
	protected $db;
	
	/**
	 * Create new service
	 *
	 * @param Database $db the database service
	 */
	public function __construct(Database $db) {
		$this->db = $db;
	}
	
	/**
	 * Insert a delayed email into the queue
	 *
	 * @param int    $recipient_guid    the recipient of the email
	 * @param string $delivery_interval the desired interval of the recipient
	 * @param mixed  $item              the email to queue
	 *
	 * @return bool
	 */
	public function queueEmail(int $recipient_guid, string $delivery_interval, $item): bool {
		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'recipient_guid' => $insert->param($recipient_guid, ELGG_VALUE_GUID),
			'delivery_interval' => $insert->param($delivery_interval, ELGG_VALUE_STRING),
			'data' => $insert->param(serialize($item), ELGG_VALUE_STRING),
			'timestamp' => $insert->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP),
		]);
		
		return $this->db->insertData($insert) !== false;
	}
	
	/**
	 * Get a row from the queue
	 *
	 * @param int $id the ID to fetch
	 *
	 * @return null|DatabaseRecord database row
	 */
	public function getRow(int $id): ?DatabaseRecord {
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('id', '=', $id, ELGG_VALUE_ID));
		
		return $this->db->getDataRow($select, [$this, 'rowToRecord']);
	}
	
	/**
	 * Get all the rows in the queue for a given recipient
	 *
	 * @param int    $recipient_guid    the recipient
	 * @param string $delivery_interval the interval for the recipient
	 * @param int    $timestamp         (optional) all queue items before time (default: now)
	 *
	 * @return DatabaseRecord[] database rows
	 */
	public function getRecipientRows(int $recipient_guid, string $delivery_interval, int $timestamp = null): array {
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('recipient_guid', '=', $recipient_guid, ELGG_VALUE_GUID))
			->andWhere($select->compare('delivery_interval', '=', $delivery_interval, ELGG_VALUE_STRING))
			->andWhere($select->compare('timestamp', '<', $timestamp ?? $this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP));
		
		return $this->db->getData($select, [$this, 'rowToRecord']);
	}
	
	/**
	 * Get the queued items from the database for a given interval
	 *
	 * @param string $delivery_interval the delivery interval to get
	 * @param int    $timestamp         (optional) all queue items before time (default: now)
	 *
	 * @return DatabaseRecord[]
	 */
	public function getIntervalRows(string $delivery_interval, int $timestamp = null): array {
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('delivery_interval', '=', $delivery_interval, ELGG_VALUE_STRING))
			->andWhere($select->compare('timestamp', '<', $timestamp ?? $this->getCurrentTime()->getTimestamp()))
			->orderBy('recipient_guid', 'ASC')
			->addOrderBy('timestamp', 'ASC');
		
		return $this->db->getData($select, [$this, 'rowToRecord']);
	}
	
	/**
	 * Remove a queue items from the database
	 *
	 * @param int $id the row to delete
	 *
	 * @return int number of deleted rows
	 */
	public function deleteRow(int $id): int {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('id', '=', $id, ELGG_VALUE_ID));
		
		return $this->db->deleteData($delete);
	}
	
	/**
	 * Delete all the queue items from the database for the given recipient and interval
	 *
	 * @param int    $recipient_guid    the recipient
	 * @param string $delivery_interval the interval for the recipient
	 * @param int    $timestamp         (optional) all queue items before time (default: now)
	 *
	 * @return int number of deleted rows
	 */
	public function deleteRecipientRows(int $recipient_guid, string $delivery_interval, int $timestamp = null): int {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('recipient_guid', '=', $recipient_guid, ELGG_VALUE_GUID))
			->andWhere($delete->compare('delivery_interval', '=', $delivery_interval, ELGG_VALUE_STRING))
			->andWhere($delete->compare('timestamp', '<', $timestamp ?? $this->getCurrentTime()->getTimestamp(), ELGG_VALUE_INTEGER));
		
		return $this->db->deleteData($delete);
	}
	
	/**
	 * Update the queued notifications for the recipient to a new delivery interval
	 *
	 * @param int    $recipient_guid    the recipient
	 * @param string $delivery_interval the new delivery interval
	 *
	 * @return bool
	 */
	public function updateRecipientInterval(int $recipient_guid, string $delivery_interval): bool {
		$update = Update::table(self::TABLE_NAME);
		$update->set('delivery_interval', $update->param($delivery_interval, ELGG_VALUE_STRING))
			->where($update->compare('recipient_guid', '=', $recipient_guid, ELGG_VALUE_GUID));
		
		return $this->db->updateData($update);
	}
	
	/**
	 * Convert a database row to a managable object
	 *
	 * @param \stdClass $row the database record
	 *
	 * @return DatabaseRecord
	 */
	public function rowToRecord(\stdClass $row): DatabaseRecord {
		return new DatabaseRecord($row);
	}
}
