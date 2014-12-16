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
	
	/** @var Pool */
	private $cache;

	/** @var Database */
	private $db;

	/** @var Logger */
	private $logger;

	/** @var string */
	private $table;

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
	function get($name) {
		$name = trim($name);
	
		// cannot store anything longer than 255 characters in db, so catch here
		if (elgg_strlen($name) > 255) {
			$this->logger->error("The name length for configuration variables cannot be greater than 255");
			return false;
		}

		return $this->cache->get($name, function() use ($name) {
			$escaped_name = $this->db->sanitizeString($name);
			$result = $this->db->getDataRow("SELECT * FROM {$this->table} WHERE name = '$escaped_name'");
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
	function set($name, $value) {
		$name = trim($name);
	
		// cannot store anything longer than 255 characters in db, so catch before we set
		if (elgg_strlen($name) > 255) {
			$this->logger->error("The name length for configuration variables cannot be greater than 255");
			return false;
		}
	
	
		$escaped_name = $this->db->sanitizeString($name);
		$escaped_value = $this->db->sanitizeString($value);
		$success = $this->db->insertData("INSERT INTO {$this->table}"
			. " SET name = '$escaped_name', value = '$escaped_value'"
			. " ON DUPLICATE KEY UPDATE value = '$escaped_value'");

		$this->cache->put($name, $value);
	
		return $success !== false;
	}
	
	/**
	 * Load entire datalist in memory.
	 * 
	 * This could cause OOM problems if the datalists table is large.
	 * 
	 * @todo make a list of datalists that we want to get in one grab
	 * 
	 * @return array
	 * @access private
	 */
	function loadAll() {
		$result = $this->db->getData("SELECT * FROM {$this->table}");
		$map = array();
		if (is_array($result)) {
			foreach ($result as $row) {
				$map[$row->name] = $row->value;
				$this->cache->put($row->name, $row->value);
			}
		}

		return $map;
	}
	
	/**
	 * Run a function one time per installation.
	 *
	 * If you pass a timestamp as the second argument, it will run the function
	 * only if (i) it has never been run before or (ii) the timestamp is >=
	 * the last time it was run.
	 *
	 * @warning Functions are determined by their name.  If you change the name of a function
	 * it will be run again.
	 *
	 * @tip Use $timelastupdatedcheck in your plugins init function to perform automated
	 * upgrades.  Schedule a function to run once and pass the timestamp of the new release.
	 * This will cause the run once function to be run on all installations.  To perform
	 * additional upgrades, create new functions for each release.
	 *
	 * @warning The function name cannot be longer than 255 characters long due to
	 * the current schema for the datalist table.
	 *
	 * @internal A datalist entry $functioname is created with the value of time().
	 *
	 * @param string $functionname         The name of the function you want to run.
	 * @param int    $timelastupdatedcheck A UNIX timestamp. If time() is > than this,
	 *                                     this function will be run again.
	 *
	 * @return bool
	 * @todo deprecate
	 */
	function runFunctionOnce($functionname, $timelastupdatedcheck = 0) {
		$lastupdated = $this->get($functionname);
		if ($lastupdated) {
			$lastupdated = (int) $lastupdated;
		} elseif ($lastupdated !== false) {
			$lastupdated = 0;
		} else {
			// unable to check datalist
			return false;
		}
		if (is_callable($functionname) && $lastupdated <= $timelastupdatedcheck) {
			$functionname();
			$this->set($functionname, time());
			return true;
		} else {
			return false;
		}
	}
}