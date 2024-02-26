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
	public const TABLE_NAME = 'delayed_email_queue';
	
	/**
	 * Create new service
	 *
	 * @param Database $db the database service
	 */
	public function __construct(protected Database $db) {
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
		
		return $this->db->insertData($insert) !== 0;
	}
	
	/**
	 * Get a row from the queue
	 *
	 * @param int $id the ID to fetch
	 *
	 * @return DatabaseRecord|null database row
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
	 * @param int      $recipient_guid    the recipient
	 * @param string   $delivery_interval the interval for the recipient
	 * @param null|int $timestamp         (optional) all queue items before time (default: now)
	 * @param int      $max_results       (optional) maximum number of rows to return
	 *
	 * @return DatabaseRecord[] database rows
	 */
	public function getRecipientRows(int $recipient_guid, string $delivery_interval, int $timestamp = null, int $max_results = 0): array {
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('recipient_guid', '=', $recipient_guid, ELGG_VALUE_GUID))
			->andWhere($select->compare('delivery_interval', '=', $delivery_interval, ELGG_VALUE_STRING))
			->andWhere($select->compare('timestamp', '<', $timestamp ?? $this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP))
			->orderBy('timestamp', 'ASC')
			->addOrderBy('id', 'ASC');
		
		if ($max_results > 0) {
			$select->setMaxResults($max_results);
		}
		
		return $this->db->getData($select, [$this, 'rowToRecord']);
	}
	
	/**
	 * Fetch the GUID of the next recipient to process
	 *
	 * @param string   $delivery_interval the delivery interval to get
	 * @param null|int $timestamp         (optional) based on queue items before time (default: now)
	 *
	 * @return null|int
	 */
	public function getNextRecipientGUID(string $delivery_interval, int $timestamp = null): ?int {
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('recipient_guid')
			->where($select->compare('delivery_interval', '=', $delivery_interval, ELGG_VALUE_STRING))
			->andWhere($select->compare('timestamp', '<', $timestamp ?? $this->getCurrentTime()->getTimestamp()))
			->orderBy('timestamp', 'ASC')
			->addOrderBy('id', 'ASC')
			->setMaxResults(1);
		
		$row = $this->db->getDataRow($select);
		if (empty($row)) {
			return null;
		}
		
		return (int) $row->recipient_guid;
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
	 * @param int      $recipient_guid    the recipient
	 * @param string   $delivery_interval the interval for the recipient
	 * @param null|int $timestamp         (optional) all queue items before time (default: now)
	 * @param int      $max_id            (optional) the max row ID to remove (this includes the given row ID)
	 *
	 * @return int number of deleted rows
	 */
	public function deleteRecipientRows(int $recipient_guid, string $delivery_interval, int $timestamp = null, int $max_id = 0): int {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('recipient_guid', '=', $recipient_guid, ELGG_VALUE_GUID))
			->andWhere($delete->compare('delivery_interval', '=', $delivery_interval, ELGG_VALUE_STRING))
			->andWhere($delete->compare('timestamp', '<', $timestamp ?? $this->getCurrentTime()->getTimestamp(), ELGG_VALUE_INTEGER))
			->orderBy('timestamp', 'ASC')
			->addOrderBy('id', 'ASC');
		
		if ($max_id > 0) {
			$delete->andWhere($delete->compare('id', '<=', $max_id, ELGG_VALUE_ID));
		}
		
		return $this->db->deleteData($delete);
	}
	
	/**
	 * Deletes all the queue items from the database for the given recipient
	 *
	 * @param int $recipient_guid the recipient
	 *
	 * @return int number of deleted rows
	 */
	public function deleteAllRecipientRows(int $recipient_guid): int {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('recipient_guid', '=', $recipient_guid, ELGG_VALUE_GUID));
		
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
	 * Convert a database row to a manageable object
	 *
	 * @param \stdClass $row the database record
	 *
	 * @return DatabaseRecord
	 */
	public function rowToRecord(\stdClass $row): DatabaseRecord {
		return new DatabaseRecord($row);
	}
}
