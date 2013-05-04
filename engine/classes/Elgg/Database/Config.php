<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.9.0
 */
class Elgg_Database_Config {

	const READ = 'read';
	const WRITE = 'write';
	const READ_WRITE = 'readwrite';

	/** @var stdClass $config Elgg's config object */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param stdClass $config Elgg's $CONFIG object
	 */
	public function __construct(stdClass $config) {
		$this->config = $config;
	}

	/**
	 * Get the database table prefix
	 *
	 * @return string
	 */
	public function getTablePrefix() {
		return $this->config->dbprefix;
	}

	/**
	 * Is the query cache enabled?
	 *
	 * @return bool
	 */
	public function isQueryCacheEnabled() {
		if (isset($this->config->db_disable_query_cache)) {
			return !$this->config->db_disable_query_cache;
		}

		return true;
	}

	/**
	 * Are the read and write connections separate?
	 *
	 * @return bool
	 */
	public function isDatabaseSplit() {
		if (isset($this->config->db) && isset($this->config->db['split'])) {
			return $this->config->db['split'];
		}

		// this was the recommend structure from Elgg 1.0 to 1.8
		if (isset($this->config->db) && isset($this->config->db->split)) {
			return $this->config->db->split;
		}

		return false;
	}

	/**
	 * Get the connection configuration
	 *
	 * The parameters are in an array like this:
	 * array(
	 *	'host' => 'xxx',
	 *  'user' => 'xxx',
	 *  'password' => 'xxx',
	 *  'database' => 'xxx',
	 * )
	 *
	 * @param int $type The connection type: READ, WRITE, READ_WRITE
	 * @return array
	 */
	public function getConnectionConfig($type = self::READ_WRITE) {
		$config = array();
		switch ($type) {
			case self::READ:
			case self::WRITE:
				$config = $this->getParticularConnectionConfig($type);
				break;
			default:
				$config = $this->getGeneralConnectionConfig();
				break;
		}

		return $config;
	}

	/**
	 * Get the read/write database connection information
	 *
	 * @return array
	 */
	protected function getGeneralConnectionConfig() {
		return array(
			'host' => $this->config->dbhost,
			'user' => $this->config->dbuser,
			'password' => $this->config->dbpass,
			'database' => $this->config->dbname,
		);
	}

	/**
	 * Get connection information for reading or writing
	 *
	 * @param string $type Connection type: 'write' or 'read'
	 * @return array
	 */
	protected function getParticularConnectionConfig($type) {
		if (is_object($this->config->db[$type])) {
			// old style single connection (Elgg < 1.9)
			$config = array(
				'host' => $this->config->db[$type]->dbhost,
				'user' => $this->config->db[$type]->dbuser,
				'password' => $this->config->db[$type]->dbpass,
				'database' => $this->config->db[$type]->dbname,
			);
		} else if (array_key_exists('dbhost', $this->config->db[$type])) {
			// new style single connection
			$config = array(
				'host' => $this->config->db[$type]['dbhost'],
				'user' => $this->config->db[$type]['dbuser'],
				'password' => $this->config->db[$type]['dbpass'],
				'database' => $this->config->db[$type]['dbname'],
			);
		} else if (is_object(current($this->config->db[$type]))) {
			// old style multiple connections
			$index = array_rand($this->config->db[$type]);
			$config = array(
				'host' => $this->config->db[$type][$index]->dbhost,
				'user' => $this->config->db[$type][$index]->dbuser,
				'password' => $this->config->db[$type][$index]->dbpass,
				'database' => $this->config->db[$type][$index]->dbname,
			);
		} else {
			// new style multiple connections
			$index = array_rand($this->config->db[$type]);
			$config = array(
				'host' => $this->config->db[$type][$index]['dbhost'],
				'user' => $this->config->db[$type][$index]['dbuser'],
				'password' => $this->config->db[$type][$index]['dbpass'],
				'database' => $this->config->db[$type][$index]['dbname'],
			);
		}

		return $config;
	}
}
