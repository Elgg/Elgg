<?php
/**
 * Elgg configuration procedural code.
 *
 * Includes functions for manipulating the configuration values stored in the database
 * Plugin authors should use the {@link elgg_get_config()}, {@link elgg_set_config()},
 * {@link elgg_save_config()}, and {@unset_config()} functions to access or update
 * config values.
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
 * Get the URL for the current (or specified) site
 *
 * @param int $site_guid The GUID of the site whose URL we want to grab
 * @return string
 * @since 1.8.0
 */
function elgg_get_site_url($site_guid = 0) {
	if ($site_guid == 0) {
		global $CONFIG;
		return $CONFIG->wwwroot;
	}

	$site = get_entity($site_guid);

	if (!$site instanceof ElggSite) {
		return false;
	}
	/* @var ElggSite $site */

	return $site->url;
}

/**
 * Get the plugin path for this installation
 *
 * @return string
 * @since 1.8.0
 */
function elgg_get_plugins_path() {
	global $CONFIG;
	return $CONFIG->pluginspath;
}

/**
 * Get the data directory path for this installation
 *
 * @return string
 * @since 1.8.0
 */
function elgg_get_data_path() {
	global $CONFIG;
	return $CONFIG->dataroot;
}

/**
 * Get the root directory path for this installation
 *
 * @return string
 * @since 1.8.0
 */
function elgg_get_root_path() {
	global $CONFIG;
	return $CONFIG->path;
}

/**
 * Get an Elgg configuration value
 *
 * @param string $name      Name of the configuration value
 * @param int    $site_guid null for installation setting, 0 for default site
 *
 * @return mixed Configuration value or null if it does not exist
 * @since 1.8.0
 */
function elgg_get_config($name, $site_guid = 0) {
	global $CONFIG;

	$name = trim($name);

	// do not return $CONFIG value if asking for non-current site
	if (($site_guid === 0 || $site_guid === null || $site_guid == $CONFIG->site_guid) && isset($CONFIG->$name)) {
		return $CONFIG->$name;
	}

	if ($site_guid === null) {
		// installation wide setting
		$value = datalist_get($name);
	} else {
		if ($site_guid == 0) {
			$site_guid = (int) $CONFIG->site_guid;
		}

		// hit DB only if we're not sure if value isn't already loaded
		if (!isset($CONFIG->site_config_loaded) || $site_guid != $CONFIG->site_guid) {
			// site specific setting
			$value = get_config($name, $site_guid);
		} else {
			$value = null;
		}
	}

	// @todo document why we don't cache false
	if ($value === false) {
		return null;
	}

	if ($site_guid == $CONFIG->site_guid || $site_guid === null) {
		$CONFIG->$name = $value;
	}

	return $value;
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
 * @param string $name      Configuration name (cannot be greater than 255 characters)
 * @param mixed  $value     Configuration value. Should be string for installation setting
 * @param int    $site_guid null for installation setting, 0 for default site
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_save_config($name, $value, $site_guid = 0) {
	global $CONFIG;

	$name = trim($name);

	if (strlen($name) > 255) {
		elgg_log("The name length for configuration variables cannot be greater than 255", "ERROR");
		return false;
	}

	if ($site_guid === null) {
		if (is_array($value) || is_object($value)) {
			return false;
		}
		$result = datalist_set($name, $value);
	} else {
		if ($site_guid == 0) {
			$site_guid = (int) $CONFIG->site_guid;
		}
		$result = set_config($name, $value, $site_guid);
	}

	if ($site_guid === null || $site_guid == $CONFIG->site_guid) {
		elgg_set_config($name, $value);
	}

	return $result;
}

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
function datalist_get($name) {
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
		$datalist_memcache = new ElggMemcache('datalist_memcache');
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
function datalist_set($name, $value) {
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
		$datalist_memcache = new ElggMemcache('datalist_memcache');
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
function run_function_once($functionname, $timelastupdatedcheck = 0) {
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

/**
 * Removes a config setting.
 *
 * @internal These settings are stored in the dbprefix_config table and read
 * during system boot into $CONFIG.
 *
 * @param string $name      The name of the field.
 * @param int    $site_guid Optionally, the GUID of the site (default: current site).
 *
 * @return bool Success or failure
 *
 * @see get_config()
 * @see set_config()
 */
function unset_config($name, $site_guid = 0) {
	global $CONFIG;

	$name = trim($name);

	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = (int) $CONFIG->site_guid;
	}

	if ($site_guid == $CONFIG->site_guid && isset($CONFIG->$name)) {
		unset($CONFIG->$name);
	}

	$escaped_name = sanitize_string($name);
	$query = "DELETE FROM {$CONFIG->dbprefix}config WHERE name = '$escaped_name' AND site_guid = $site_guid";

	return delete_data($query) !== false;
}

/**
 * Add or update a config setting.
 *
 * Plugin authors should use elgg_set_config().
 *
 * If the config name already exists, it will be updated to the new value.
 *
 * @warning Names should be selected so as not to collide with the names for the
 * datalist (application configuration)
 *
 * @internal These settings are stored in the dbprefix_config table and read
 * during system boot into $CONFIG.
 *
 * @internal The value is serialized so we maintain type information.
 *
 * @param string $name      The name of the configuration value
 * @param mixed  $value     Its value
 * @param int    $site_guid Optionally, the GUID of the site (current site is assumed by default)
 *
 * @return bool
 * @see unset_config()
 * @see get_config()
 * @access private
 */
function set_config($name, $value, $site_guid = 0) {
	global $CONFIG;

	$name = trim($name);

	// cannot store anything longer than 255 characters in db, so catch before we set
	if (elgg_strlen($name) > 255) {
		elgg_log("The name length for configuration variables cannot be greater than 255", "ERROR");
		return false;
	}

	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = (int) $CONFIG->site_guid;
	}

	if ($site_guid == $CONFIG->site_guid) {
		$CONFIG->$name = $value;
	}

	$escaped_name = sanitize_string($name);
	$escaped_value = sanitize_string(serialize($value));
	$result = insert_data("INSERT INTO {$CONFIG->dbprefix}config
		SET name = '$escaped_name', value = '$escaped_value', site_guid = $site_guid
		ON DUPLICATE KEY UPDATE value = '$escaped_value'");

	return $result !== false;
}

/**
 * Gets a configuration value
 *
 * Plugin authors should use elgg_get_config().
 *
 * @internal These settings are stored in the dbprefix_config table and read
 * during system boot into $CONFIG.
 *
 * @param string $name      The name of the config value
 * @param int    $site_guid Optionally, the GUID of the site (default: current site)
 *
 * @return mixed|null
 * @see set_config()
 * @see unset_config()
 * @access private
 */
function get_config($name, $site_guid = 0) {
	global $CONFIG;

	$name = trim($name);

	$site_guid = (int) $site_guid;

	// check for deprecated values.
	// @todo might be a better spot to define this?
	$new_name = false;
	switch($name) {
		case 'viewpath':
			$new_name = 'view_path';
			break;

		case 'pluginspath':
			$new_name = 'plugins_path';
			break;

		case 'sitename':
			$new_name = 'site_name';
			break;
	}

	// @todo these haven't really been implemented in Elgg 1.8. Complete in 1.9.
	// show dep message
	if ($new_name) {
		//	$msg = "Config value $name has been renamed as $new_name";
		$name = $new_name;
		//	elgg_deprecated_notice($msg, $dep_version);
	}

	if ($site_guid == 0) {
		$site_guid = (int) $CONFIG->site_guid;
	}

	// decide from where to return the value
	if ($site_guid == $CONFIG->site_guid && isset($CONFIG->$name)) {
		return $CONFIG->$name;
	}

	$escaped_name = sanitize_string($name);
	$result = get_data_row("SELECT value FROM {$CONFIG->dbprefix}config
		WHERE name = '$escaped_name' AND site_guid = $site_guid");

	if ($result) {
		$result = unserialize($result->value);

		if ($site_guid == $CONFIG->site_guid) {
			$CONFIG->$name = $result;
		}

		return $result;
	}

	return null;
}

/**
 * Loads all configuration values from the dbprefix_config table into $CONFIG.
 *
 * @param int $site_guid Optionally, the GUID of the site (current site is assumed by default)
 *
 * @return bool
 * @access private
 */
function _elgg_get_all_config($site_guid = 0) {
	global $CONFIG;

	$site_guid = (int) $site_guid;

	if ($site_guid == 0) {
		$site_guid = (int) $CONFIG->site_guid;
	}

	if ($result = get_data("SELECT * FROM {$CONFIG->dbprefix}config WHERE site_guid = $site_guid")) {
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
 * Loads configuration related to this site
 *
 * This runs on engine boot and loads from the config database table and the
 * site entity. It runs after the application configuration is loaded by
 * _elgg_load_application_config().
 *
 * @see _elgg_engine_boot()
 *
 * @access private
 */
function _elgg_load_site_config() {
	global $CONFIG;

	$CONFIG->site_guid = (int) datalist_get('default_site');
	$CONFIG->site_id = $CONFIG->site_guid;
	$CONFIG->site = get_entity($CONFIG->site_guid);
	if (!$CONFIG->site) {
		throw new InstallationException("Unable to handle this request. This site is not configured or the database is down.");
	}

	$CONFIG->wwwroot = $CONFIG->site->url;
	$CONFIG->sitename = $CONFIG->site->name;
	$CONFIG->sitedescription = $CONFIG->site->description;
	$CONFIG->siteemail = $CONFIG->site->email;
	$CONFIG->url = $CONFIG->wwwroot;

	_elgg_get_all_config();
	// gives hint to elgg_get_config function how to approach missing values
	$CONFIG->site_config_loaded = true;

	if (!empty($CONFIG->debug)) {
		_elgg_services()->logger->setLevel($CONFIG->debug);
		_elgg_services()->logger->setDisplay(true);
	}
}

/**
 * Loads configuration related to Elgg as an application
 *
 * This runs on the engine boot and loads from the datalists database table.
 *
 * @see _elgg_engine_boot()
 *
 * @access private
 */
function _elgg_load_application_config() {
	global $CONFIG, $DATALIST_CACHE;

	$install_root = str_replace("\\", "/", dirname(dirname(dirname(__FILE__))));
	$defaults = array(
		'path' => "$install_root/",
		'view_path' => "$install_root/views/",
		'plugins_path' => "$install_root/mod/",
		'language' => 'en',

		// compatibility with old names for plugins not using elgg_get_config()
		'viewpath' => "$install_root/views/",
		'pluginspath' => "$install_root/mod/",
	);

	foreach ($defaults as $name => $value) {
		if (empty($CONFIG->$name)) {
			$CONFIG->$name = $value;
		}
	}

	// set cookie values for session and remember me
	if (!isset($CONFIG->cookies)) {
		$CONFIG->cookies = array();
	}
	if (!isset($CONFIG->cookies['session'])) {
		$CONFIG->cookies['session'] = array();
	}
	$session_defaults = session_get_cookie_params();
	$session_defaults['name'] = 'Elgg';
	$CONFIG->cookies['session'] = array_merge($session_defaults, $CONFIG->cookies['session']);
	if (!isset($CONFIG->cookies['remember_me'])) {
		$CONFIG->cookies['remember_me'] = array();
	}
	$session_defaults['name'] = 'elggperm';
	$session_defaults['expire'] = strtotime("+30 days");
	$CONFIG->cookies['remember_me'] = array_merge($session_defaults, $CONFIG->cookies['remember_me']);

	// load entire datalist
	// This can cause OOM problems when the datalists table is large
	// @todo make a list of datalists that we want to get in one grab
	if (!is_memcache_available()) {
		$result = get_data("SELECT * FROM {$CONFIG->dbprefix}datalists");
		if ($result) {
			foreach ($result as $row) {
				$DATALIST_CACHE[$row->name] = $row->value;
			}
		}
	}

	$path = datalist_get('path');
	if (!empty($path)) {
		$CONFIG->path = $path;
	}

	// allow sites to set dataroot and simplecache_enabled in settings.php
	if (isset($CONFIG->dataroot)) {
		$CONFIG->dataroot = sanitise_filepath($CONFIG->dataroot);
		$CONFIG->dataroot_in_settings = true;
	} else {
		$dataroot = datalist_get('dataroot');
		if (!empty($dataroot)) {
			$CONFIG->dataroot = $dataroot;
		}
		$CONFIG->dataroot_in_settings = false;
	}
	if (isset($CONFIG->simplecache_enabled)) {
		$CONFIG->simplecache_enabled_in_settings = true;
	} else {
		$simplecache_enabled = datalist_get('simplecache_enabled');
		if ($simplecache_enabled !== false) {
			$CONFIG->simplecache_enabled = $simplecache_enabled;
		} else {
			$CONFIG->simplecache_enabled = 1;
		}
		$CONFIG->simplecache_enabled_in_settings = false;
	}

	$system_cache_enabled = datalist_get('system_cache_enabled');
	if ($system_cache_enabled !== false) {
		$CONFIG->system_cache_enabled = $system_cache_enabled;
	} else {
		$CONFIG->system_cache_enabled = 1;
	}

	// initialize context here so it is set before the first get_input call
	$CONFIG->context = array();

	// needs to be set before system, init for links in html head
	$CONFIG->lastcache = (int)datalist_get("simplecache_lastupdate");

	$CONFIG->i18n_loaded_from_cache = false;

	// this must be synced with the enum for the entities table
	$CONFIG->entity_types = array('group', 'object', 'site', 'user');
}

/**
 * @access private
 */
function _elgg_config_test($hook, $type, $tests) {
	global $CONFIG;
	$tests[] = "{$CONFIG->path}engine/tests/ElggCoreConfigTest.php";
	return $tests;
}

elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_config_test');
