<?php
namespace Elgg\Database;


/**
 * An array of key value pairs from the datalists table.
 *
 * Used as a cache in datalist functions.
 *
 * @global array $DATALIST_CACHE
 */
global $DATALIST_CACHE;
$DATALIST_CACHE = array();


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
		global $CONFIG, $DATALIST_CACHE;
	
		$name = trim($name);
	
		// cannot store anything longer than 255 characters in db, so catch here
		if (elgg_strlen($name) > 255) {
			elgg_log("The name length for configuration variables cannot be greater than 255", "ERROR");
			return false;
		}
	
		if (isset($DATALIST_CACHE[$name])) {
			return $DATALIST_CACHE[$name];
		}
	
		// If memcache enabled then cache value in memcache
		$value = null;
		static $datalist_memcache = null;
		if (!$datalist_memcache && is_memcache_available()) {
			$datalist_memcache = new \ElggMemcache('datalist_memcache');
		}
		if ($datalist_memcache) {
			$value = $datalist_memcache->load($name);
		}
		// @todo cannot cache 0 or false?
		if ($value) {
			return $value;
		}
	
		// not in cache and not in memcache so check database
		$escaped_name = sanitize_string($name);
		$result = get_data_row("SELECT * FROM {$CONFIG->dbprefix}datalists WHERE name = '$escaped_name'");
		if ($result) {
			$DATALIST_CACHE[$result->name] = $result->value;
	
			// Cache it if memcache is available
			if ($datalist_memcache) {
				$datalist_memcache->save($result->name, $result->value);
			}
	
			return $result->value;
		}
	
		return null;
	}
	
	/**
	 * Set the value for a datalist element.
	 * 
	 * Plugin authors should use elgg_save_config() and pass null for the site GUID.
	 * 
	 * @warning Names should be selected so as not to collide with the names for the
	 * site config.
	 * 
	 * @warning Values set through datalist_set() are not available in $CONFIG until
	 * next page load.
	 *
	 * @param string $name  The name of the datalist
	 * @param string $value The new value
	 *
	 * @return bool
	 * @access private
	 */
	function set($name, $value) {
		global $CONFIG, $DATALIST_CACHE;
	
		$name = trim($name);
	
		// cannot store anything longer than 255 characters in db, so catch before we set
		if (elgg_strlen($name) > 255) {
			elgg_log("The name length for configuration variables cannot be greater than 255", "ERROR");
			return false;
		}
	
		// If memcache is available then invalidate the cached copy
		static $datalist_memcache = null;
		if ((!$datalist_memcache) && (is_memcache_available())) {
			$datalist_memcache = new \ElggMemcache('datalist_memcache');
		}
	
		if ($datalist_memcache) {
			$datalist_memcache->delete($name);
		}
	
		$escaped_name = sanitize_string($name);
		$escaped_value = sanitize_string($value);
		$success = insert_data("INSERT INTO {$CONFIG->dbprefix}datalists"
			. " SET name = '$escaped_name', value = '$escaped_value'"
			. " ON DUPLICATE KEY UPDATE value = '$escaped_value'");
	
		if ($success !== false) {
			$DATALIST_CACHE[$name] = $value;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Load entire datalist in memory.
	 * 
	 * This could cause OOM problems if the datalists table is large.
	 * 
	 * @todo make a list of datalists that we want to get in one grab
	 * 
	 * @return void
	 * @access private
	 */
	function loadAll() {
		$result = get_data("SELECT * FROM {$CONFIG->dbprefix}datalists");
		if ($result) {
			foreach ($result as $row) {
				$DATALIST_CACHE[$row->name] = $row->value;
			}
		}
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
		$lastupdated = datalist_get($functionname);
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
			datalist_set($functionname, time());
			return true;
		} else {
			return false;
		}
	}
}