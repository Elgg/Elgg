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
		$res = $this->db->insertRow('queue', [
			'name' => (string)$this->name,
			'data' => serialize($item),
			'timestamp' => time(),
		]);

		return $res !== false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function dequeue() {
		$update = "
			UPDATE {$this->db->prefix('queue')}
			SET worker = :worker
			WHERE name = :name AND worker IS NULL
			ORDER BY id ASC LIMIT 1
		";
		$params = [
			':worker' => (string)$this->workerId,
			':name' => (string)$this->name,
		];
		$num = $this->db->updateData($update, true, $params);

		if ($num === 1) {
			$select = "
				SELECT data FROM {$this->db->prefix('queue')}
				WHERE worker = :worker
			";
			$params = [
				':worker' => (string)$this->workerId,
			];
			$obj = $this->db->getDataRow($select, null, $params);
			if ($obj) {
				$data = unserialize($obj->data);
				$delete = "
					DELETE FROM {$this->db->prefix('queue')}
					WHERE name = :name AND worker = :worker
				";
				$params = [
					':worker' => (string)$this->workerId,
					':name' => (string)$this->name,
				];
				$this->db->deleteData($delete, $params);
				return $data;
			}
		}

		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear() {
		$this->db->deleteRows('queue', ['name' => $this->name]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function size() {
		$sql = "
			SELECT COUNT(id) AS total
			FROM {$this->db->prefix('queue')}
			WHERE name = :name
		";
		$params = [':name' => $this->name];
		$result = $this->db->getDataRow($sql, null, $params);

		return (int)$result->total;
	}
}

