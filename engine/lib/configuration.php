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
 * Removes a config setting.
 *
 * @internal
 * These settings are stored in the dbprefix_config table and read during system
 * boot into $CONFIG.
 *
 * @param string $name The name of the field.
 * @param int $site_guid Optionally, the GUID of the site (current site is assumed by default).
 * @return int|false The number of affected rows or false on error.
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

	return delete_data("delete from {$CONFIG->dbprefix}config where name='$name' and site_guid=$site_guid");
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
 * @param string $name The name of the configuration value
 * @param string $value Its value
 * @param int $site_guid Optionally, the GUID of the site (current site is assumed by default)
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

	return insert_data("insert into {$CONFIG->dbprefix}config set name = '{$name}', value = '{$value}', site_guid = {$site_guid}");
}

/**
 * Gets a configuration value
 *
 * @internal
 * These settings are stored in the dbprefix_config table and read during system
 * boot into $CONFIG.
 *
 * @param string $name The name of the config value
 * @param int $site_guid Optionally, the GUID of the site (current site is assumed by default)
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
 */
function set_default_config() {
	global $CONFIG;

	if (empty($CONFIG->path)) {
		$CONFIG->path = str_replace("\\","/",dirname(dirname(dirname(__FILE__)))) . "/";
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
		$pathpart = str_replace("//","/",str_replace($_SERVER['DOCUMENT_ROOT'],"",$CONFIG->path));
		if (substr($pathpart,0,1) != "/") {
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
function configuration_init() {
	global $CONFIG;

	if (is_installed() || is_db_installed()) {
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

		return true;
	}
}

register_elgg_event_handler('boot', 'system', 'configuration_init', 10);