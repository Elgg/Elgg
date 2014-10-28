<?php

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
class Elgg_Queue_DatabaseQueue implements Elgg_Queue_Queue {

	/** @var string Name of the queue */
	protected $name;

	/** @var Elgg_Database Database adapter */
	protected $db;

	/** @var string The identifier of the worker pulling from the queue */
	protected $workerId;

	/**
	 * Create a queue
	 *
	 * @param string        $name Name of the queue. Must be less than 256 characters.
	 * @param Elgg_Database $db   Database adapter
	 */
	public function __construct($name, Elgg_Database $db) {
		$this->db = $db;
		$this->name = $this->db->sanitizeString($name);
		$this->workerId = $this->db->sanitizeString(md5(microtime() . getmypid()));
	}

	/**
	 * {@inheritdoc}
	 */
	public function enqueue($item) {
		$prefix = $this->db->getTablePrefix();
		$blob = $this->db->sanitizeString(serialize($item));
		$time = time();
		$query = "INSERT INTO {$prefix}queue
			SET name = '$this->name', data = '$blob', timestamp = $time";
		return $this->db->insertData($query) !== false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function dequeue() {
		$prefix = $this->db->getTablePrefix();
		$update = "UPDATE {$prefix}queue
			SET worker = '$this->workerId'
			WHERE name = '$this->name' AND worker IS NULL
			ORDER BY id ASC LIMIT 1";
		$num = $this->db->updateData($update, true);
		if ($num === 1) {
			$select = "SELECT data FROM {$prefix}queue
				WHERE worker = '$this->workerId'";
			$obj = $this->db->getDataRow($select);
			if ($obj) {
				$data = unserialize($obj->data);
				$delete = "DELETE FROM {$prefix}queue
					WHERE name = '$this->name' AND worker = '$this->workerId'";
				$this->db->deleteData($delete);
				return $data;
			}
		}

		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear() {
		$prefix = $this->db->getTablePrefix();
		$this->db->deleteData("DELETE FROM {$prefix}queue WHERE name = '$this->name'");
	}

	/**
	 * {@inheritdoc}
	 */
	public function size() {
		$prefix = $this->db->getTablePrefix();
		$result = $this->db->getDataRow("SELECT COUNT(id) AS total FROM {$prefix}queue WHERE name = '$this->name'");
		return (int)$result->total;
	}
}
