<?php

namespace Elgg\Database;

use Elgg\Cache\Pool;
use Elgg\Database;
use Elgg\Logger;

/**
 * Persistent, installation-wide key-value storage.
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class Datalist {
	
	const MAX_NAME_LENGTH = 255;
	
	/** @var Pool */
	protected $cache;

	/** @var Database */
	protected $db;

	/** @var Logger */
	protected $logger;

	/** @var string */
	protected $table;

	/**
	 * Constructor
	 *
	 * @param Pool     $cache  Some kind of caching implementation
	 * @param Database $db     The database
	 * @param Logger   $logger A logger
	 * @param string   $table  The name of the datalists table, including prefix
	 */
	public function __construct(Pool $cache, Database $db, Logger $logger, $table) {
		$this->cache = $cache;
		$this->db = $db;
		$this->logger = $logger;
		$this->table = $table;
	}

	/**
	 * Set cache. The BootService injects a pre-populated cache here. The constructor requires a cache as
	 * well because the installer doesn't fully boot.
	 *
	 * @param Pool $pool Cache
	 * @return void
	 * @access private
	 * @see \Elgg\BootService::boot
	 */
	public function setCache(Pool $pool) {
		$this->cache = $pool;
	}

	/**
	 * Get the value of a datalist element.
	 *
	 * Plugin authors should use elgg_get_config() and pass null for the site GUID.
	 *
	 * @internal Datalists are stored in the datalist table.
	 *
	 * @tip Use datalists to store information common to a full installation.
	 *
	 * @param string $name The name of the datalist
	 * @return string|null|false String if value exists, null if doesn't, false on error
	 * @access private
	 */
	public function get($name) {
		$name = trim($name);
		if (!$this->validateName($name)) {
			return false;
		}

		return $this->cache->get($name, function() use ($name) {

			$sql = "SELECT * FROM {$this->table} WHERE name = :name";
			$params = [
				':name' => $name,
			];
			$result = $this->db->getDataRow($sql, null, $params);
			return $result ? $result->value : null;
		});
	}

	/**
	 * Set the value for a datalist element.
	 *
	 * Plugin authors should use elgg_save_config() and pass null for the site GUID.
	 *
	 * @warning Names should be selected so as not to collide with the names for the
	 * site config.
	 *
	 * @warning Values set here are not available in $CONFIG until next page load.
	 *
	 * @param string $name  The name of the datalist
	 * @param string $value The new value
	 *
	 * @return bool
	 * @access private
	 */
	public function set($name, $value) {
		$name = trim($name);
		if (!$this->validateName($name)) {
			return false;
		}

		$sql = "
			INSERT INTO {$this->table}
			SET name = :name, value = :value
			ON DUPLICATE KEY UPDATE value = :value
		";

		$params = [
			':name' => $name,
			':value' => $value,
		];

		$success = $this->db->insertData($sql, $params);
		
		$this->cache->put($name, $value);

		return $success !== false;
	}

	/**
	 * Verify a datalist name is valid
	 *
	 * @param string $name Datalist name to be checked
	 *
	 * @return bool
	 */
	protected function validateName($name) {

		$max = self::MAX_NAME_LENGTH;

		// Can't use elgg_strlen() because not available until core loaded.
		if (is_callable('mb_strlen')) {
			$is_valid = (mb_strlen($name) <= $max);
		} else {
			$is_valid = (strlen($name) <= $max);
		}

		if (!$is_valid) {
			$this->logger->error("The name length for configuration variables cannot be greater than $max");
		}

		return $is_valid;
	}

}
