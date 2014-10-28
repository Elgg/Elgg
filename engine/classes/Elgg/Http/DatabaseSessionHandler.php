<?php

/**
 * Database session handler
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Http
 */
class Elgg_Http_DatabaseSessionHandler implements Elgg_Http_SessionHandler {

	/** @var Elgg_Database $db */
	protected $db;

	/**
	 * Constructor
	 *
	 * @param Elgg_Database $db The database
	 */
	public function __construct(Elgg_Database $db) {
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

		$id = sanitize_string($session_id);
		$query = "SELECT * FROM {$this->db->getTablePrefix()}users_sessions WHERE session='$id'";
		$result = $this->db->getDataRow($query);
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
		$id = sanitize_string($session_id);
		$time = time();
		$sess_data_sanitised = sanitize_string($session_data);

		$query = "REPLACE INTO {$this->db->getTablePrefix()}users_sessions
			(session, ts, data) VALUES
			('$id', '$time', '$sess_data_sanitised')";

		if ($this->db->insertData($query) !== false) {
			return true;
		} else {
			return false;
		}
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

		$id = sanitize_string($session_id);
		$query = "DELETE FROM {$this->db->getTablePrefix()}users_sessions WHERE session='$id'";
		return (bool) $this->db->deleteData($query);
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
