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
		$name = $this->db->sanitizeString($this->name);
		$blob = $this->db->sanitizeString(serialize($item));
		$time = time();

		$query = "INSERT INTO {$prefix}queue
			SET name = '$name', data = '$blob', timestamp = $time";
		return $this->db->insertData($query) !== false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function dequeue() {
		$prefix = $this->db->prefix;
		$name = $this->db->sanitizeString($this->name);
		$worker_id = $this->db->sanitizeString($this->workerId);

		$update = "UPDATE {$prefix}queue 
			SET worker = '$worker_id'
			WHERE name = '$name' AND worker IS NULL
			ORDER BY id ASC LIMIT 1";
		$num = $this->db->updateData($update, true);
		if ($num === 1) {
			$select = "SELECT data FROM {$prefix}queue
				WHERE worker = '$worker_id'";
			$obj = $this->db->getDataRow($select);
			if ($obj) {
				$data = unserialize($obj->data);
				$delete = "DELETE FROM {$prefix}queue
					WHERE name = '$name' AND worker = '$worker_id'";
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
		$prefix = $this->db->prefix;
		$name = $this->db->sanitizeString($this->name);

		$this->db->deleteData("DELETE FROM {$prefix}queue WHERE name = '$name'");
	}

	/**
	 * {@inheritdoc}
	 */
	public function size() {
		$prefix = $this->db->prefix;
		$name = $this->db->sanitizeString($this->name);

		$result = $this->db->getDataRow("SELECT COUNT(id) AS total FROM {$prefix}queue WHERE name = '$name'");
		return (int)$result->total;
	}
}

