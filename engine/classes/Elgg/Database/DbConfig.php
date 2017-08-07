<?php
namespace Elgg\Database;

use Elgg\Config;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 * @since  1.9.0
 */
class DbConfig {

	const READ = 'read';
	const WRITE = 'write';
	const READ_WRITE = 'readwrite';

	protected $db;
	protected $dbprefix;
	protected $dbhost;
	protected $dbuser;
	protected $dbpass;
	protected $dbname;
	protected $db_disable_query_cache;
	protected $dbencoding;

	/**
	 * Constructor
	 *
	 * @param \stdClass $config Object with keys:
	 *  db
	 *  dbprefix
	 *  dbhost
	 *  dbuser
	 *  dbpass
	 *  dbname
	 *  db_disable_query_cache
	 *  dbencoding
	 */
	public function __construct(\stdClass $config) {
		foreach (array_keys(get_class_vars(__CLASS__)) as $prop) {
			$this->{$prop} = isset($config->{$prop}) ? $config->{$prop} : null;
		}
	}

	/**
	 * Construct from an Elgg Config
	 *
	 * @param Config $config Elgg config
	 *
	 * @return DbConfig
	 */
	public static function fromElggConfig(Config $config) {
		$obj = new \stdClass();
		foreach (array_keys(get_class_vars(__CLASS__)) as $prop) {
			$obj->{$prop} = $config->{$prop};
		}
		return new self($obj);
	}

	/**
	 * Get the database table prefix
	 *
	 * @return string
	 */
	public function getTablePrefix() {
		return $this->dbprefix;
	}

	/**
	 * Is the query cache enabled?
	 *
	 * @return bool
	 */
	public function isQueryCacheEnabled() {
		if ($this->db_disable_query_cache !== null) {
			return !$this->db_disable_query_cache;
		}

		return true;
	}

	/**
	 * Are the read and write connections separate?
	 *
	 * @return bool
	 */
	public function isDatabaseSplit() {
		if (isset($this->db['split'])) {
			return $this->db['split'];
		}

		// this was the recommend structure from Elgg 1.0 to 1.8
		if (isset($this->db->split)) {
			return $this->db->split;
		}

		return false;
	}

	/**
	 * Get the connection configuration
	 *
	 * @note You must check isDatabaseSplit before using READ or WRITE for $type
	 *
	 * The parameters are in an array like this:
	 * array(
	 *	'host' => 'xxx',
	 *  'user' => 'xxx',
	 *  'password' => 'xxx',
	 *  'database' => 'xxx',
	 *  'encoding' => 'xxx',
	 *  'prefix' => 'xxx',
	 * )
	 *
	 * @param string $type The connection type: READ, WRITE, READ_WRITE
	 * @return array
	 */
	public function getConnectionConfig($type = self::READ_WRITE) {
		switch ($type) {
			case self::READ:
			case self::WRITE:
				$config = $this->getParticularConnectionConfig($type);
				break;
			default:
				$config = $this->getGeneralConnectionConfig();
				break;
		}

		$config['encoding'] = $this->dbencoding ? $this->dbencoding : 'utf8';
		$config['prefix'] = $this->dbprefix;

		return $config;
	}

	/**
	 * Get the read/write database connection information
	 *
	 * @return array
	 */
	protected function getGeneralConnectionConfig() {
		return [
			'host' => $this->dbhost,
			'user' => $this->dbuser,
			'password' => $this->dbpass,
			'database' => $this->dbname,
		];
	}

	/**
	 * Get connection information for reading or writing
	 *
	 * @param string $type Connection type: 'write' or 'read'
	 * @return array
	 */
	protected function getParticularConnectionConfig($type) {
		if (is_object($this->db[$type])) {
			// old style single connection (Elgg < 1.9)
			$config = [
				'host' => $this->db[$type]->dbhost,
				'user' => $this->db[$type]->dbuser,
				'password' => $this->db[$type]->dbpass,
				'database' => $this->db[$type]->dbname,
			];
		} else if (array_key_exists('dbhost', $this->db[$type])) {
			// new style single connection
			$config = [
				'host' => $this->db[$type]['dbhost'],
				'user' => $this->db[$type]['dbuser'],
				'password' => $this->db[$type]['dbpass'],
				'database' => $this->db[$type]['dbname'],
			];
		} else if (is_object(current($this->db[$type]))) {
			// old style multiple connections
			$index = array_rand($this->db[$type]);
			$config = [
				'host' => $this->db[$type][$index]->dbhost,
				'user' => $this->db[$type][$index]->dbuser,
				'password' => $this->db[$type][$index]->dbpass,
				'database' => $this->db[$type][$index]->dbname,
			];
		} else {
			// new style multiple connections
			$index = array_rand($this->db[$type]);
			$config = [
				'host' => $this->db[$type][$index]['dbhost'],
				'user' => $this->db[$type][$index]['dbuser'],
				'password' => $this->db[$type][$index]['dbpass'],
				'database' => $this->db[$type][$index]['dbname'],
			];
		}

		return $config;
	}
}

