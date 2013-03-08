<?php

/**
 * Database session handler
 *
 * @access private
 */
class Elgg_Http_DatabaseSessionHandler implements Elgg_Http_SessionHandler {

	/** @var ElggDatabase $db */
	protected $db;

	/**
	 * Constructor
	 *
	 * @param ElggDatabase $db The database
	 */
	public function __construct(ElggDatabase $db) {
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
		global $CONFIG;

		$id = sanitize_string($session_id);
		$query = "SELECT * FROM {$CONFIG->dbprefix}users_sessions WHERE session='$id'";
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
		global $CONFIG;

		$id = sanitize_string($session_id);
		$time = time();
		$sess_data_sanitised = sanitize_string($session_data);

		$query = "REPLACE INTO {$CONFIG->dbprefix}users_sessions
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
		global $CONFIG;

		$id = sanitize_string($session_id);
		$query = "DELETE FROM {$CONFIG->dbprefix}users_sessions WHERE session='$id'";
		return (bool) $this->db->deleteData($query);
	}

	/**
	 * {@inheritDoc}
	 */
	public function gc($max_lifetime) {
		global $CONFIG;

		$life = time() - $max_lifetime;
		$query = "DELETE FROM {$CONFIG->dbprefix}users_sessions WHERE ts < '$life'";
		return (bool) $this->db->deleteData($query);
	}

}
