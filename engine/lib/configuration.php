<?php
/**
 * Elgg configuration procedural code.
 *
 * Includes functions for manipulating the configuration values stored in the database
 * Plugin authors should use the {@link elgg_get_config()}, {@link elgg_set_config()},
 * {@link elgg_save_config()}, and {@elgg_remove_config()} functions to access or update
 * config values.
 *
 * Elgg's configuration is split among 1 table and 1 file:
 * - dbprefix_config
 * - engine/settings.php (See {@link settings.example.php})
 *
 * Upon system boot, all values in dbprefix_config are read into $CONFIG.
 */

use Elgg\Project\Paths;

/**
 * Get the URL for the current (or specified) site, ending with "/".
 *
 * @return string
 * @since 1.8.0
 */
function elgg_get_site_url() {
	return _elgg_config()->wwwroot;
}

/**
 * Get the plugin path for this installation, ending with slash.
 *
 * @return string
 * @since 1.8.0
 */
function elgg_get_plugins_path() {
	return _elgg_services()->plugins->getPath();
}

/**
 * Get the data directory path for this installation, ending with slash.
 *
 * @return string
 * @since 1.8.0
 */
function elgg_get_data_path() {
	return _elgg_config()->dataroot;
}

/**
 * Get the cache directory path for this installation, ending with slash.
 *
 * If not set in settings, the data path will be returned.
 *
 * @return string
 */
function elgg_get_cache_path() {
	$path = _elgg_config()->cacheroot ? : elgg_get_data_path() . 'caches/';
	return Paths::sanitize($path);
}

/**
 * Get the asset cache directory path for this installation, ending with slash.
 *
 * If not set in settings, the cache path will be returned.
 *
 * @return string
 */
function elgg_get_asset_path() {
	$path = _elgg_config()->assetroot ? : elgg_get_cache_path() . 'views_simplecache/';
	return Paths::sanitize($path);
}

/**
 * Get the project path (where composer is installed), ending with slash.
 *
 * Note: This is not the same as the Elgg root! In the Elgg 1.x series, Elgg
 * was always at the install root, but as of 2.0, Elgg can be installed as a
 * composer dependency, so you cannot assume that it the install root anymore.
 *
 * @return string
 * @since 1.8.0
 */
function elgg_get_root_path() {
	return Paths::project();
}

/**
 * /path/to/elgg/engine with no trailing slash.
 *
 * @return string
 */
function elgg_get_engine_path() {
	return Paths::elgg() . 'engine';
}

/**
 * Get an Elgg configuration value
 *
 * @param string $name    Name of the configuration value
 * @param mixed  $default (optional) default value if configuration value is not set
 *
 * @return mixed Configuration value or the default value if it does not exist
 * @since 1.8.0
 */
function elgg_get_config($name, $default = null) {
	if ($name == 'icon_sizes') {
		$msg = 'The config value "icon_sizes" is deprecated. Use elgg_get_icon_sizes()';
		elgg_deprecated_notice($msg, '2.2');
	}

	if (!_elgg_config()->hasValue($name)) {
		elgg_log("Config value for '$name' is not set'", 'INFO');
		return $default;
	}

	return _elgg_config()->$name;
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
	_elgg_config()->$name = $value;
}

/**
 * Save a configuration setting
 *
 * @param string $name  Configuration name (cannot be greater than 255 characters)
 * @param mixed  $value Configuration value. Should be string for installation setting
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_save_config($name, $value) {
	return _elgg_config()->save($name, $value);
}

/**
 * Removes a config setting.
 *
 * @param string $name The name of the field.
 *
 * @return bool Success or failure
 */
function elgg_remove_config($name) {
	return _elgg_config()->remove($name);
}

/**
 * Get the Elgg config service
 *
 * @return \Elgg\Config
 * @access private
 */
function _elgg_config() {
	$config = _elgg_services()->config;
	if (!$config) {
		throw new \RuntimeException(__FUNCTION__ . ' can not be called before an instance of ' . \Elgg\Application::class . ' is bootstrapped');
	}

	return $config;
}

/**
 * Register unit tests
 *
 * @param string $hook  'unit_test'
 * @param string $type  'system'
 * @param array  $tests current return value
 *
 * @return array
 *
 * @access private
 * @codeCoverageIgnore
 */
function _elgg_config_test($hook, $type, $tests) {
	$tests[] = ElggCoreConfigTest::class;
	return $tests;
}

/**
 * Returns a configuration array of icon sizes
 *
 * @param string $entity_type    Entity type
 * @param string $entity_subtype Entity subtype
 * @param string $type           The name of the icon. e.g., 'icon', 'cover_photo'
 * @return array
 */
function elgg_get_icon_sizes($entity_type = null, $entity_subtype = null, $type = 'icon') {
	return _elgg_services()->iconService->getSizes($entity_type, $entity_subtype, $type);
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$hooks->registerHandler('unit_test', 'system', '_elgg_config_test');
};
