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
 */

use Elgg\Filesystem\Directory;

/**
 * Get the URL for the current (or specified) site
 *
 * @param int $site_guid The GUID of the site whose URL we want to grab
 * @return string
 * @since 1.8.0
 */
function elgg_get_site_url($site_guid = 0) {
	return _elgg_services()->config->getSiteUrl($site_guid);
}

/**
 * Get the plugin path for this installation
 *
 * @return string
 * @since 1.8.0
 */
function elgg_get_plugins_path() {
	return _elgg_services()->config->getPluginsPath();
}

/**
 * Get the data directory path for this installation
 *
 * @return string
 * @since 1.8.0
 */
function elgg_get_data_path() {
	return _elgg_services()->config->getDataPath();
}

/**
 * Get the root directory path for this installation
 * 
 * Note: This is not the same as the Elgg root! In the Elgg 1.x series, Elgg
 * was always at the install root, but as of 2.0, Elgg can be installed as a
 * composer dependency, so you cannot assume that it the install root anymore.
 *
 * @return string
 * @since 1.8.0
 */
function elgg_get_root_path() {
	return Directory\Local::root()->getPath('/');
}

/**
 * /path/to/elgg/engine
 * 
 * No trailing slash
 * 
 * @return string
 */
function elgg_get_engine_path() {
	return dirname(__DIR__);
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
	if ($name === 'siteemail') {
		$msg = 'The config value "siteemail" is deprecated. Use elgg_get_site_entity()->email';
		elgg_deprecated_notice($msg, '2.1');
	}

	return _elgg_services()->config->get($name, $site_guid);
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
	return _elgg_services()->config->set($name, $value);
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
	return _elgg_services()->config->save($name, $value, $site_guid);
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
function datalist_get($name) {
	return _elgg_services()->datalist->get($name);
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
	return _elgg_services()->datalist->set($name, $value);
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
	return _elgg_services()->datalist->runFunctionOnce($functionname, $timelastupdatedcheck);
}

/**
 * Removes a config setting.
 *
 * @note Internal: These settings are stored in the dbprefix_config table and read
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
	return _elgg_services()->configTable->remove($name, $site_guid);
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
	return _elgg_services()->configTable->set($name, $value, $site_guid);
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
	return _elgg_services()->configTable->get($name, $site_guid);
}

/**
 * @access private
 */
function _elgg_config_test($hook, $type, $tests) {
	$tests[] = \Elgg\Application::elggDir()->getPath("engine/tests/ElggCoreConfigTest.php");
	return $tests;
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$hooks->registerHandler('unit_test', 'system', '_elgg_config_test');
};
