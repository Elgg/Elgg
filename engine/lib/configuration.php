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

use Elgg\Filesystem\Directory;

/**
 * Get the URL for the current (or specified) site
 *
 * @return string
 * @since 1.8.0
 */
function elgg_get_site_url() {
	return _elgg_services()->config->getSiteUrl();
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
 * Get the cache directory path for this installation.
 *
 * If not set in settings.php, the data path will be returned.
 *
 * @return string
 */
function elgg_get_cache_path() {
	return _elgg_services()->config->getCachePath();
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
	return _elgg_services()->config->get('path');
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
 * @param string $name Name of the configuration value
 *
 * @return mixed Configuration value or null if it does not exist
 * @since 1.8.0
 */
function elgg_get_config($name) {
	if ($name == 'icon_sizes') {
		$msg = 'The config value "icon_sizes" is deprecated. Use elgg_get_icon_sizes()';
		elgg_deprecated_notice($msg, '2.2');
	}

	return _elgg_services()->config->get($name);
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
	_elgg_services()->config->set($name, $value);
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
	return _elgg_services()->config->save($name, $value);
}

/**
 * Removes a config setting.
 *
 * @param string $name The name of the field.
 *
 * @return bool Success or failure
 */
function elgg_remove_config($name) {
	return _elgg_services()->config->remove($name);
}

/**
 * @access private
 */
function _elgg_config_test($hook, $type, $tests) {
	$tests[] = \Elgg\Application::elggDir()->getPath("engine/tests/ElggCoreConfigTest.php");
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

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$hooks->registerHandler('unit_test', 'system', '_elgg_config_test');
};
