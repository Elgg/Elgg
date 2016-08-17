<?php
namespace Elgg\Http;

/**
 * Database session handler
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Http
 */
class DatabaseSessionHandler implements \SessionHandlerInterface {

	/** @var \Elgg\Database $db */
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
		$result = $this->db->fetchRow('users_sessions', ['session' => (string)$session_id]);
		if ($result) {
			return (string) $result->data;
		} else {
			return false;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function write($session_id, $session_data) {
		$query = "
			INSERT INTO {$this->db->getTablePrefix()}users_sessions
			(session, ts, data) VALUES
		  	(:session, :ts, :data)
			ON DUPLICATE KEY UPDATE ts = :ts, data = :data
		";
		$params = [
			':session' => (string)$session_id,
			':ts' => time(),
			':data' => (string)$session_data,
		];

		return $this->db->insertData($query, $params) !== false;
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
		return (bool) $this->db->deleteRows('users_sessions', ['session' => $session_id]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function gc($max_lifetime) {
		
		$life = time() - $max_lifetime;
		$query = "DELETE FROM {$this->db->getTablePrefix()}users_sessions WHERE ts < '$life'";
		return (bool) $this->db->deleteData($query);
	}
}
