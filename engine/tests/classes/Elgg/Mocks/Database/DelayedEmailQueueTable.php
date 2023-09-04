<?php

namespace Elgg\Mocks\Database;

use Elgg\Email\DelayedQueue\DatabaseRecord;
use Elgg\Database\DelayedEmailQueueTable as DbDelayedEmailQueueTable;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Database\Delete;

/**
 * Interfaces with the database to perform operations on the delayed_email_queue table
 *
 * @internal
 * @since 4.0
 */
class DelayedEmailQueueTable extends DbDelayedEmailQueueTable{
	
	/**
	 * @var array Database rows
	 */
	protected $rows = [];
	
	/**
	 * @var array Query specs
	 */
	protected $query_specs = [];
	
	/**
	 * @var int
	 */
	protected static $iterator = 100;
	
	/**
	 * {@inheritdoc}
	 */
	public function queueEmail(int $recipient_guid, string $delivery_interval, $item): bool {
		self::$iterator++;
		$id = self::$iterator;
		
		// lock the time to prevent testing issues
		$this->setCurrentTime();
		
		$row = (object) [
			'id' => $id,
			'recipient_guid' => $recipient_guid,
			'delivery_interval' => $delivery_interval,
			'data' => serialize($item),
			'timestamp' => $this->getCurrentTime()->getTimestamp(),
		];
		$this->addQuerySpecs($row);
		
		$result = parent::queueEmail($recipient_guid, $delivery_interval, $item);
		
		// unlock the time
		$this->resetCurrentTime();
		
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRecipientRows(int $recipient_guid, string $delivery_interval, int $timestamp = null, int $max_results = 0): array {
		$result = [];
		
		$timestamp = $timestamp ?? $this->getCurrentTime()->getTimestamp();
		
		foreach ($this->rows as $row) {
			if ($row->recipient_guid !== $recipient_guid) {
				continue;
			}
			
			if ($row->delivery_interval !== $delivery_interval) {
				continue;
			}
			
			if ($row->timestamp >= $timestamp) {
				continue;
			}
			
			$result[] = $this->rowToRecord($row);
		}
		
		if ($max_results > 0) {
			// first order
			usort($result, function($row_a, $row_b) {
				// this should be based on timestamp and id, but that's harder
				return $row_a->id - $row_b->id;
			});
			
			return array_slice($result, 0, $max_results);
		}
		
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function deleteRecipientRows(int $recipient_guid, string $delivery_interval, int $timestamp = null, int $max_id = 0): int {
		$result = 0;
		
		$timestamp = $timestamp ?? $this->getCurrentTime()->getTimestamp();
		
		foreach ($this->rows as $id => $row) {
			if ($row->recipient_guid !== $recipient_guid) {
				continue;
			}
			
			if ($row->delivery_interval !== $delivery_interval) {
				continue;
			}
			
			if ($row->timestamp >= $timestamp) {
				continue;
			}
			
			if ($max_id > 0 && $id > $max_id) {
				continue;
			}
			
			$this->clearQuerySpecs($row);
			unset($this->rows[$id]);
			
			$result++;
		}
		
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function deleteAllRecipientRows(int $recipient_guid): int {
		$result = 0;
		
		foreach ($this->rows as $id => $row) {
			if ($row->recipient_guid !== $recipient_guid) {
				continue;
			}
						
			$this->clearQuerySpecs($row);
			unset($this->rows[$id]);
			
			$result++;
		}
		
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function updateRecipientInterval(int $recipient_guid, string $delivery_interval): bool {
		foreach ($this->rows as $row) {
			if ($row->recipient_guid !== $recipient_guid) {
				continue;
			}
			
			$row->delivery_interval = $delivery_interval;
			
			$this->addQuerySpecs($row);
		}
		
		return true;
	}
	
	/**
	 * Add database query specs
	 *
	 * @param \stdClass $row new table row
	 *
	 * @return void
	 */
	protected function addQuerySpecs(\stdClass $row): void {
		
		$this->clearQuerySpecs($row);
		
		$this->rows[$row->id] = $row;
		
		// insert
		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'recipient_guid' => $insert->param($row->recipient_guid, ELGG_VALUE_GUID),
			'delivery_interval' => $insert->param($row->delivery_interval, ELGG_VALUE_STRING),
			'data' => $insert->param($row->data, ELGG_VALUE_STRING),
			'timestamp' => $insert->param($row->timestamp, ELGG_VALUE_TIMESTAMP),
		]);
		
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $insert->getSQL(),
			'params' => $insert->getParameters(),
			'insert_id' => $row->id,
		]);
		
		// select
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('id', '=', $row->id, ELGG_VALUE_ID));
		
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => function () use ($row) {
				if (isset($this->rows[$row->id])) {
					return [$this->rows[$row->id]];
				}
				
				return [];
			},
		]);
		
		// delete
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('id', '=', $row->id, ELGG_VALUE_ID));
		
		$this->query_specs[$row->id][] = $this->db->addQuerySpec([
			'sql' => $delete->getSQL(),
			'params' => $delete->getParameters(),
			'results' => function () use ($row) {
				if (isset($this->rows[$row->id])) {
					unset($this->rows[$row->id]);
					$this->clearQuerySpecs($row);
					
					return [$row->id];
				}
				
				return [];
			}
		]);
	}
	
	/**
	 * Clear database query specs
	 *
	 * @param \stdClass $row row to clear
	 *
	 * @return void
	 */
	protected function clearQuerySpecs(\stdClass $row): void {
		if (!isset($this->query_specs[$row->id])) {
			return;
		}
		
		foreach ($this->query_specs[$row->id] as $spec) {
			$this->db->removeQuerySpec($spec);
		}
		
		unset($this->query_specs[$row->id]);
	}
}
