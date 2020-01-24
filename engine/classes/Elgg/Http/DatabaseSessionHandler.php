<?php

namespace Elgg\Http;

/**
 * Database session handler
 *
 * @internal
 */
class DatabaseSessionHandler implements \SessionHandlerInterface {

	/**
	 * @var \Elgg\Database $db
	 */
	protected $db;

	/**
	 * Constructor
	 *
	 * @param \Elgg\Database $db The database
	 */
	public function __construct(\Elgg\Database $db) {
		$this->db = $db;
	}

	/**
	 * {@inheritDoc}
	 */
	public function open($save_path, $name) {
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function read($session_id) {
		
		$query = "SELECT *
			FROM {$this->db->prefix}users_sessions
			WHERE session = :session_id";
		$params = [
			':session_id' => $session_id,
		];
		
		$result = $this->db->getDataRow($query, null, $params);
		if (!empty($result)) {
			return (string) $result->data;
		}
		
		return '';
	}

	/**
	 * {@inheritDoc}
	 */
	public function write($session_id, $session_data) {
		
		if (elgg_get_config('_disable_session_save')) {
			return true;
		}
		
		$query = "INSERT INTO {$this->db->prefix}users_sessions
			(session, ts, data) VALUES
			(:session_id, :time, :data)
			ON DUPLICATE KEY UPDATE ts = VALUES(ts), data = VALUES(data)";
		$params = [
			':session_id' => $session_id,
			':time' => time(),
			':data' => $session_data,
		];

		if ($this->db->insertData($query, $params) !== false) {
			return true;
		}
		
		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function close() {
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function destroy($session_id) {
		
		$query = "DELETE FROM {$this->db->prefix}users_sessions
			WHERE session = :session_id";
		$params = [
			':session_id' => $session_id,
		];
		
		return (bool) $this->db->deleteData($query, $params);
	}

	/**
	 * {@inheritDoc}
	 */
	public function gc($max_lifetime) {
		
		$query = "DELETE FROM {$this->db->prefix}users_sessions
			WHERE ts < :life";
		$params = [
			':life' => (time() - $max_lifetime),
		];
		
		return (bool) $this->db->deleteData($query, $params);
	}
}
