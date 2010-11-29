<?php
/**
 * Elgg configuration procedural code.
 *
 * Includes functions for manipulating the configuration values stored in the database
 * Plugin authors should use the {@link get_config()}, {@link set_config()},
 * and {@unset_config()} functions to access or update config values.
 *
 * Elgg's configuration is split among 2 tables and 1 file:
 * - dbprefix_config
 * - dbprefix_datalists
 * - engine/settings.php (See {@link settings.example.php})
 *
 * Upon system boot, all values in dbprefix_config are read into $CONFIG.
 *
 * @package Elgg.Core
 * @subpackage Configuration
 */

/**
 * Get an Elgg configuration value
 *
 * @param string $name      Name of the configuration value
 * @param int    $site_guid NULL for installation setting, 0 for default site
 *
 * @return mixed Configuration value or false if it does not exist
 * @since 1.8.0
 */
function elgg_get_config($name, $site_guid = 0) {
	global $CONFIG;

	$name = trim($name);

	if (isset($CONFIG->$name)) {
		return $CONFIG->$name;
	}

	if ($site_guid === NULL) {
		// installation wide setting
		$value = datalist_get($name);
	} else {
		// site specific setting
		if ($site_guid == 0) {
			$site_guid = (int) $CONFIG->site_id;
		}
		$value = get_config($name, $site_guid);
	}

	if ($value !== false) {
		$CONFIG->$name = $value;
		return $value;
	}
	
	return false;
}

/**
 * Set an Elgg configuration value
 *
 * @warning This does not persist the configuration setting. Use elgg_save_config()
 *
 * @param string $name  Name of the configuration value
 * @param mixed  $value Value
 *
 * @return void
 * @since 1.8.0
 */
function elgg_set_config($name, $value) {
	global $CONFIG;

	$name = trim($name);

	$CONFIG->$name = $value;
}

/**
 * Save a configuration setting
 *
 * @param string $name      Configuration name
 * @param mixed  $value     Configuration value. Should be string for installation setting
 * @param int    $site_guid NULL for installation setting, 0 for default site
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_save_config($name, $value, $site_guid = 0) {
	global $CONFIG;

	$name = trim($name);

	elgg_set_config($name, $value);

	if ($site_guid === NULL) {
		if (is_array($value) || is_object($value)) {
			return false;
		}
		return datalist_set($name, $value);
	} else {
		if ($site_guid == 0) {
			$site_guid = (int) $CONFIG->site_id;
		}
		return set_config($name, $value, $site_guid);
	}
}

/**
 * Check that installation has completed and the database is populated.
 *
 * @throws InstallationException
 * @return void
 */
function verify_installation() {
	global $CONFIG;

	if (isset($CONFIG->installed)) {
		return $CONFIG->installed;
	}

	try {
		$dblink = get_db_link('read');
		if (!$dblink) {
			throw new DatabaseException();
		}

		mysql_query("SELECT value FROM {$CONFIG->dbprefix}datalists WHERE name = 'installed'", $dblink);
		if (mysql_errno($dblink) > 0) {
			throw new DatabaseException();
		}

		$CONFIG->installed = true;

	} catch (DatabaseException $e) {
		throw new InstallationException(elgg_echo('InstallationException:SiteNotInstalled'));
	}
}

/**
 * An array of key value pairs from the datalists table.
 *
 * Used as a cache in datalist functions.
 *
 * @global array $DATALIST_CACHE
 */
$DATALIST_CACHE = array();

/**
 * Get the value of a datalist element.
 *
 * @internal Datalists are stored in the datalist table.
 *
 * @tip Use datalists to store information common to a full installation.
 *
 * @param string $name The name of the datalist element
 *
 * @return string|false The datalist value or false if it doesn't exist.
 */
function datalist_get($name) {
	global $CONFIG, $DATALIST_CACHE;

	$name = sanitise_string($name);
	if (isset($DATALIST_CACHE[$name])) {
		return $DATALIST_CACHE[$name];
	}

	// If memcache enabled then cache value in memcache
	$value = null;
	static $datalist_memcache;
	if ((!$datalist_memcache) && (is_memcache_available())) {
		$datalist_memcache = new ElggMemcache('datalist_memcache');
	}
	if ($datalist_memcache) {
		$value = $datalist_memcache->load($name);
	}
	if ($value) {
		return $value;
	}

	// [Marcus Povey 20090217 : Now retrieving all datalist values on first
	// load as this saves about 9 queries per page]
	// This also causes OOM problems when the datalists table is large
	// @todo make a list of datalists that we want to get in one grab
	$result = get_data("SELECT * from {$CONFIG->dbprefix}datalists");
	if ($result) {
		foreach ($result as $row) {
			$DATALIST_CACHE[$row->name] = $row->value;

			// Cache it if memcache is available
			if ($datalist_memcache) {
				$datalist_memcache->save($row->name, $row->value);
			}
		}

		if (isset($DATALIST_CACHE[$name])) {
			return $DATALIST_CACHE[$name];
		}
	}

	return false;
}

/**
 * Set the value for a datalist element.
 *
 * @param string $name  The name of the datalist
 * @param string $value The new value
 *
 * @return true
 */
function datalist_set($name, $value) {
	global $CONFIG, $DATALIST_CACHE;

	$name = sanitise_string($name);
	$value = sanitise_string($value);

	// If memcache is available then invalidate the cached copy
	static $datalist_memcache;
	if ((!$datalist_memcache) && (is_memcache_available())) {
		$datalist_memcache = new ElggMemcache('datalist_memcache');
	}

	if ($datalist_memcache) {
		$datalist_memcache->delete($name);
	}

	insert_data("INSERT into {$CONFIG->dbprefix}datalists"
		. " set name = '{$name}', value = '{$value}'"
		. " ON DUPLICATE KEY UPDATE value='{$value}'");

	$DATALIST_CACHE[$name] = $value;

	return true;
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
 * @internal A datalist entry $functioname is created with the value of time().
 *
 * @param string $functionname         The name of the function you want to run.
 * @param int    $timelastupdatedcheck A UNIX timestamp. If time() is > than this,
 *                                     this function will be run again.
 *
 * @return bool
 */
function run_function_once($functionname, $timelastupdatedcheck = 0) {
	if ($lastupdated = datalist_get($functionname)) {
		$lastupdated = (int) $lastupdated;
	} else {
		$lastupdated = 0;
	}
	if (is_callable($functionname) && $lastupdated <= $timelastupdatedcheck) {
		$functionname();
		datalist_set($functionname, time());
		return true;
	} else {
		return false;
	}
}

/**
 * Removes a config setting.
 *
 * @internal
 * These settings are stored in the dbprefix_config table and read during system
 * boot into $CONFIG.
 *
 * @param string $name      The name of the field.
 * @param int    $site_guid Optionally, the GUID of the site (current site is assumed by default).
 *
 * @return int|false The number of affected rows or false on error.
 *
 * @see get_config()
 * @see set_config()
 */
function unset_config($name, $site_guid = 0) {
	global $CONFIG;

	$name = sanitise_string($name);
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = (int) $CONFIG->site_id;
	}

	$query = "delete from {$CONFIG->dbprefix}config where name='$name' and site_guid=$site_guid";
	return delete_data($query);
}

/**
 * Add or update a config setting.
 *
 * If the config name already exists, it will be updated to the new value.
 *
 * @internal
 * These settings are stored in the dbprefix_config table and read during system
 * boot into $CONFIG.
 *
 * @param string $name      The name of the configuration value
 * @param string $value     Its value
 * @param int    $site_guid Optionally, the GUID of the site (current site is assumed by default)
 *
 * @return 0
 * @todo The config table doens't have numeric primary keys so insert_data returns 0.
 * @todo Use "INSERT ... ON DUPLICATE KEY UPDATE" instead of trying to delete then add.
 * @see unset_config()
 * @see get_config()
 */
function set_config($name, $value, $site_guid = 0) {
	global $CONFIG;

	// Unset existing
	unset_config($name, $site_guid);

	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = (int) $CONFIG->site_id;
	}
	$CONFIG->$name = $value;
	$value = sanitise_string(serialize($value));

	$query = "insert into {$CONFIG->dbprefix}config"
		. " set name = '{$name}', value = '{$value}', site_guid = {$site_guid}";
	return insert_data($query);
}

/**
 * Gets a configuration value
 *
 * @internal
 * These settings are stored in the dbprefix_config table and read during system
 * boot into $CONFIG.
 *
 * @param string $name      The name of the config value
 * @param int    $site_guid Optionally, the GUID of the site (current site is assumed by default)
 *
 * @return mixed|false
 * @see set_config()
 * @see unset_config()
 */
function get_config($name, $site_guid = 0) {
	global $CONFIG;

	if (isset($CONFIG->$name)) {
		return $CONFIG->$name;
	}
	$name = sanitise_string($name);
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = (int) $CONFIG->site_id;
	}
	if ($result = get_data_row("SELECT value FROM {$CONFIG->dbprefix}config
		WHERE name = '{$name}' and site_guid = {$site_guid}")) {
		$result = $result->value;
		$result = unserialize($result->value);
		$CONFIG->$name = $result;
		return $result;
	}

	return false;
}

/**
 * Loads all configuration values from the dbprefix_config table into $CONFIG.
 *
 * @param int $site_guid Optionally, the GUID of the site (current site is assumed by default)
 *
 * @return bool
 */
function get_all_config($site_guid = 0) {
	global $CONFIG;

	$site_guid = (int) $site_guid;

	if ($site_guid == 0) {
		$site_guid = (int) $CONFIG->site_id;
	}

	if ($result = get_data("SELECT * from {$CONFIG->dbprefix}config where site_guid = {$site_guid}")) {
		foreach ($result as $r) {
			$name = $r->name;
			$value = $r->value;
			$CONFIG->$name = unserialize($value);
		}

		return true;
	}
	return false;
}

/**
 * Sets defaults for or attempts to autodetect some common config values and
 * loads them into $CONFIG.
 *
 * @return void
 */
function set_default_config() {
	global $CONFIG;

	if (empty($CONFIG->path)) {
		$CONFIG->path = str_replace("\\", "/", dirname(dirname(dirname(__FILE__)))) . "/";
	}

	if (empty($CONFIG->viewpath)) {
		$CONFIG->viewpath = $CONFIG->path . "views/";
	}

	if (empty($CONFIG->pluginspath)) {
		$CONFIG->pluginspath = $CONFIG->path . "mod/";
	}

	if (empty($CONFIG->wwwroot)) {
		/*
		$CONFIG->wwwroot = "http://" . $_SERVER['SERVER_NAME'];

		$request = $_SERVER['REQUEST_URI'];

		if (strripos($request,"/") < (strlen($request) - 1)) {
			// addressing a file directly, not a dir
			$request = substr($request, 0, strripos($request,"/")+1);
		}

		$CONFIG->wwwroot .= $request;
		*/
		$pathpart = str_replace("//", "/", str_replace($_SERVER['DOCUMENT_ROOT'], "", $CONFIG->path));
		if (substr($pathpart, 0, 1) != "/") {
			$pathpart = "/" . $pathpart;
		}
		$CONFIG->wwwroot = "http://" . $_SERVER['HTTP_HOST'] . $pathpart;
	}

	if (empty($CONFIG->url)) {
		$CONFIG->url = $CONFIG->wwwroot;
	}

	if (empty($CONFIG->sitename)) {
		$CONFIG->sitename = "New Elgg site";
	}

	if (empty($CONFIG->language)) {
		$CONFIG->language = "en";
	}
}

/**
 * Loads values into $CONFIG.
 *
 * If Elgg is installed, this function pulls all rows from dbprefix_config
 * and cherry picks some values from dbprefix_datalists.  This also extracts
 * some commonly used values from the default site object.
 *
 * @elgg_event boot system
 * @return true|null
 */
function configuration_boot() {
	global $CONFIG;

	$path = datalist_get('path');
	if (!empty($path)) {
		$CONFIG->path = $path;
	}
	$dataroot = datalist_get('dataroot');
	if (!empty($dataroot)) {
		$CONFIG->dataroot = $dataroot;
	}
	$simplecache_enabled = datalist_get('simplecache_enabled');
	if ($simplecache_enabled !== false) {
		$CONFIG->simplecache_enabled = $simplecache_enabled;
	} else {
		$CONFIG->simplecache_enabled = 1;
	}
	$viewpath_cache_enabled = datalist_get('viewpath_cache_enabled');
	if ($viewpath_cache_enabled !== false) {
		$CONFIG->viewpath_cache_enabled = $viewpath_cache_enabled;
	} else {
		$CONFIG->viewpath_cache_enabled = 1;
	}
	if (isset($CONFIG->site) && ($CONFIG->site instanceof ElggSite)) {
		$CONFIG->wwwroot = $CONFIG->site->url;
		$CONFIG->sitename = $CONFIG->site->name;
		$CONFIG->sitedescription = $CONFIG->site->description;
		$CONFIG->siteemail = $CONFIG->site->email;
	}
	$CONFIG->url = $CONFIG->wwwroot;

	// Load default settings from database
	get_all_config();
}

elgg_register_event_handler('boot', 'system', 'configuration_boot', 10);