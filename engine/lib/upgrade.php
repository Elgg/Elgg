<?php
/**
 * Elgg upgrade library.
 * Contains code for handling versioning and upgrades.
 *
 * @package    Elgg.Core
 * @subpackage Upgrade
 */

/**
 * Saves the processed upgrades to a dataset.
 *
 * @param array $processed_upgrades An array of processed upgrade filenames
 *                                  (not the path, just the file)
 * @return bool
 * @access private
 *
 * @todo this is still required because of the hack in the 2011010101 upgrade
 */
function elgg_set_processed_upgrades(array $processed_upgrades) {
	$processed_upgrades = array_unique($processed_upgrades);
	return datalist_set('processed_upgrades', serialize($processed_upgrades));
}

/**
 * Returns the version of the upgrade filename.
 *
 * @param string $filename The upgrade filename. No full path.
 * @return int|false
 * @since 1.8.0
 * @access private
 * @todo used by elgg_get_upgrade_files
 */
function elgg_get_upgrade_file_version($filename) {
	preg_match('/^([0-9]{10})([\.a-z0-9-_]+)?\.(php)$/i', $filename, $matches);

	if (isset($matches[1])) {
		return (int) $matches[1];
	}

	return false;
}

/**
 * Returns a list of upgrade files relative to the $upgrade_path dir.
 *
 * @param string $upgrade_path The directory that has upgrade scripts
 * @return array|false
 * @access private
 *
 * @todo the wire and groups plugins and the installer are using this
 */
function elgg_get_upgrade_files($upgrade_path = null) {
	if (!$upgrade_path) {
		$upgrade_path = elgg_get_config('path') . 'engine/lib/upgrades/';
	}
	$upgrade_path = sanitise_filepath($upgrade_path);
	$handle = opendir($upgrade_path);

	if (!$handle) {
		return false;
	}

	$upgrade_files = array();

	while ($upgrade_file = readdir($handle)) {
		// make sure this is a well formed upgrade.
		if (is_dir($upgrade_path . '$upgrade_file')) {
			continue;
		}
		$upgrade_version = elgg_get_upgrade_file_version($upgrade_file);
		if (!$upgrade_version) {
			continue;
		}
		$upgrade_files[] = $upgrade_file;
	}

	sort($upgrade_files);

	return $upgrade_files;
}

/**
 * Unlocks upgrade.
 *
 * @access private
 *
 * @todo the hack in the 2011010101 upgrade requires this
 */
function _elgg_upgrade_unlock() {
	global $CONFIG;
	delete_data("drop table {$CONFIG->dbprefix}upgrade_lock");
	elgg_log('Upgrade unlocked.', 'NOTICE');
}
