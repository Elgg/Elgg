<?php
namespace Elgg\Services;

/**
 * Describes an object that manages Elgg configuration values
 */
interface Config {

	/**
	 * Get the URL for the current (or specified) site
	 *
	 * @param int $site_guid The GUID of the site whose URL we want to grab
	 * @return string
	 */
	public function getSiteUrl($site_guid = 0);

	/**
	 * Get the plugin path for this installation
	 *
	 * @return string
	 */
	public function getPluginsPath();

	/**
	 * Get the data directory path for this installation
	 *
	 * @return string
	 */
	public function getDataPath();

	/**
	 * Get the cache directory path for this installation
	 *
	 * If not set in settings.php, the data path will be returned.
	 *
	 * @return string
	 */
	public function getCachePath();

	/**
	 * Get an Elgg configuration value, possibly loading it from the DB's config table
	 *
	 * Before application boot, it may be unsafe to call get() for some values. You should use
	 * getVolatile() before system boot.
	 *
	 * @param string $name      Name of the configuration value
	 * @param int    $site_guid null for installation setting, 0 for default site
	 *
	 * @return mixed Configuration value or null if it does not exist
	 */
	public function get($name, $site_guid = 0);

	/**
	 * Get a config value for the current site if it's already loaded. This should be used instead of
	 * reading directly from global $CONFIG.
	 *
	 * @param string $name Name of the configuration value
	 *
	 * @return mixed Returns null if value isn't set
	 */
	public function getVolatile($name);

	/**
	 * Set an Elgg configuration value
	 *
	 * @warning This does not persist the configuration setting. Use elgg_save_config()
	 *
	 * @param string $name  Name of the configuration value
	 * @param mixed  $value Value
	 *
	 * @return void
	 */
	public function set($name, $value);

	/**
	 * Save a configuration setting
	 *
	 * @param string $name      Configuration name (cannot be greater than 255 characters)
	 * @param mixed  $value     Configuration value. Should be string for installation setting
	 * @param int    $site_guid null for installation setting, 0 for default site
	 *
	 * @return bool
	 */
	public function save($name, $value, $site_guid = 0);

	/**
	 * Merge the settings file into the storage object
	 *
	 * A particular location can be specified via $CONFIG->Config_file
	 *
	 * To skip settings loading, set $CONFIG->Config_file to false
	 *
	 * @return void
	 */
	public function loadSettingsFile();
}
