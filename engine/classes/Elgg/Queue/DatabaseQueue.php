<?php

namespace Elgg\Queue;

use Elgg\Database\Delete;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Database\Update;
use Elgg\Traits\TimeUsing;

/**
 * FIFO queue that uses the database for persistence
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @internal
 *
 * @since 1.9.0
 */
class DatabaseQueue implements \Elgg\Queue\Queue {

	use TimeUsing;
	
	/**
	 * @var string name of the queue database table
	 */
	const TABLE_NAME = 'queue';
	
	/**
	 * @var string Name of the queue
	 */
	protected $name;

	/**
	 * @var \Elgg\Database Database adapter
	 */
	protected $db;

	/**
	 * @var string The identifier of the worker pulling from the queue
	 */
	protected $workerId;

	/**
	 * Create a queue
	 *
	 * @param string         $name Name of the queue. Must be less than 256 characters.
	 * @param \Elgg\Database $db   Database adapter
	 */
	public function __construct(string $name, \Elgg\Database $db) {
		$this->db = $db;
		$this->name = $name;
		$this->workerId = md5(microtime() . getmypid());
	}

	/**
	 * {@inheritdoc}
	 */
	public function enqueue($item) {
		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'name' => $insert->param($this->name, ELGG_VALUE_STRING),
			'data' => $insert->param(serialize($item), ELGG_VALUE_STRING),
			'timestamp' => $insert->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP),
		]);
		
		return $this->db->insertData($insert) !== false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function dequeue() {
		// get a record for processing
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('name', '=', $this->name, ELGG_VALUE_STRING))
			->andWhere($select->expr()->isNull('worker'))
			->orderBy('id', 'ASC')
			->setMaxResults(1);
		
		$row = $this->db->getDataRow($select);
		if (empty($row)) {
			return;
		}
		
		// lock a record for processing
		$update = Update::table(self::TABLE_NAME);
		$update->set('worker', $update->param($this->workerId, ELGG_VALUE_STRING))
			->where($update->compare('name', '=', $this->name, ELGG_VALUE_STRING))
			->andWhere($update->compare('id', '=', $row->id, ELGG_VALUE_ID))
			->andWhere($update->expr()->isNull('worker'));
		
		if ($this->db->updateData($update, true) !== 1) {
			return;
		}
		
		// remove locked record from database
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('id', '=', $row->id, ELGG_VALUE_ID));
		
		$this->db->deleteData($delete);
		
		return unserialize($row->data);
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear() {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('name', '=', $this->name, ELGG_VALUE_STRING));
		
		$this->db->deleteData($delete);
	}

	/**
	 * {@inheritdoc}
	 */
	public function size() {
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('COUNT(*) AS total')
			->where($select->compare('name', '=', $this->name, ELGG_VALUE_STRING));
		
		$result = $this->db->getDataRow($select);
		return (int) $result->total;
	}
}
