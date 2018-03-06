<?php

namespace Elgg\Queue;

/**
 * FIFO queue that uses the database for persistence
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Queue
 * @since      1.9.0
 */
class DatabaseQueue implements \Elgg\Queue\Queue {

	/** @var string Name of the queue */
	protected $name;

	/** @var \Elgg\Database Database adapter */
	protected $db;

	/** @var string The identifier of the worker pulling from the queue */
	protected $workerId;

	/**
	 * Create a queue
	 *
	 * @param string         $name Name of the queue. Must be less than 256 characters.
	 * @param \Elgg\Database $db   Database adapter
	 */
	public function __construct($name, \Elgg\Database $db) {
		$this->db = $db;
		$this->name = $name;
		$this->workerId = md5(microtime() . getmypid());
	}

	/**
	 * {@inheritdoc}
	 */
	public function enqueue($item) {
		$prefix = $this->db->prefix;
		
		$query = "INSERT INTO {$prefix}queue
			(name, data, timestamp)
			VALUES
			(:name, :data, :timestamp)";
		$params = [
			':name' => $this->name,
			':data' => serialize($item),
			':timestamp' => time(),
		];
		return $this->db->insertData($query, $params) !== false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function dequeue() {
		$prefix = $this->db->prefix;
		$name = $this->name;
		$worker_id = $this->workerId;
		
		$update = "UPDATE {$prefix}queue
			SET worker = :worker
			WHERE name = :name AND worker IS NULL
			ORDER BY id ASC LIMIT 1";
		$update_params = [
			':worker' => $worker_id,
			':name' => $name,
		];
		$num = $this->db->updateData($update, true, $update_params);
		if ($num !== 1) {
			return;
		}
		
		$select = "SELECT data
			FROM {$prefix}queue
			WHERE worker = :worker
			AND name = :name";
		$select_params = [
			':worker' => $worker_id,
			':name' => $name,
		];
		$obj = $this->db->getDataRow($select, null, $select_params);
		if (empty($obj)) {
			return;
		}
		
		$delete = "DELETE FROM {$prefix}queue
			WHERE name = :name
			AND worker = :worker";
		$delete_params = [
			':worker' => $worker_id,
			':name' => $name,
		];
		$this->db->deleteData($delete, $delete_params);
		
		return unserialize($obj->data);
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear() {
		$prefix = $this->db->prefix;
		
		$sql = "DELETE FROM {$prefix}queue
			WHERE name = :name";
		$params = [
			':name' => $this->name,
		];

		$this->db->deleteData($sql, $params);
	}

	/**
	 * {@inheritdoc}
	 */
	public function size() {
		$prefix = $this->db->prefix;
		
		$sql = "SELECT COUNT(id) AS total
			FROM {$prefix}queue
			WHERE name = :name";
		$params = [
			':name' => $this->name,
		];
		
		$result = $this->db->getDataRow($sql, null, $params);
		return (int) $result->total;
	}
}
