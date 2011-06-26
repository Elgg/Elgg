<?php
/**
 * Elgg upgrade library.
 * Contains code for handling versioning and upgrades.
 *
 * @package Elgg.Core
 * @subpackage Upgrade
 */

/**
 * Run any php upgrade scripts which are required
 *
 * @param int  $version Version upgrading from.
 * @param bool $quiet   Suppress errors.  Don't use this.
 *
 * @return bool
 */
function upgrade_code($version, $quiet = FALSE) {
	global $CONFIG;

	$version = (int) $version;
	$upgrade_path = elgg_get_config('path') . 'engine/lib/upgrades/';
	$processed_upgrades = elgg_get_processed_upgrades();

	// upgrading from 1.7 to 1.8. Need to bootstrap.
	if (!$processed_upgrades) {
		elgg_upgrade_bootstrap_17_to_18();

		// grab accurate processed upgrades
		$processed_upgrades = elgg_get_processed_upgrades();
	}

	$upgrade_files = elgg_get_upgrade_files($upgrade_path);

	if ($upgrade_files === false) {
		return false;
	}

	$upgrades = elgg_get_unprocessed_upgrades($upgrade_files, $processed_upgrades);

	// Sort and execute
	sort($upgrades);

	foreach ($upgrades as $upgrade) {
		$upgrade_version = elgg_get_upgrade_file_version($upgrade);
		$success = true;

		// hide all errors.
		if ($quiet) {
			// hide include errors as well as any exceptions that might happen
			try {
				if (!@include("$upgrade_path/$upgrade")) {
					$success = false;
					error_log("Could not include $upgrade_path/$upgrade");
				}
			} catch (Exception $e) {
				$success = false;
				error_log($e->getmessage());
			}
		} else {
			if (!include("$upgrade_path/$upgrade")) {
				$success = false;
				error_log("Could not include $upgrade_path/$upgrade");
			}
		}

		if ($success) {
			// incrementally set upgrade so we know where to start if something fails.
			$processed_upgrades[] = $upgrade;

			// don't set the version to a lower number in instances where an upgrade
			// has been merged from a lower version of Elgg
			if ($upgrade_version > $version) {
				datalist_set('version', $upgrade_version);
			}

			elgg_set_processed_upgrades($processed_upgrades);
		} else {
			return false;
		}
	}

	return true;
}

/**
 * Saves the processed upgrades to a dataset.
 *
 * @param array $processed_upgrades An array of processed upgrade filenames
 *                                  (not the path, just the file)
 * @return bool
 */
function elgg_set_processed_upgrades(array $processed_upgrades) {
	$processed_upgrades = array_unique($processed_upgrades);
	return datalist_set('processed_upgrades', serialize($processed_upgrades));
}

/**
 * Gets a list of processes upgrades
 *
 * @return mixed Array of processed upgrade filenames or false
 */
function elgg_get_processed_upgrades() {
	$upgrades = datalist_get('processed_upgrades');
	$unserialized = unserialize($upgrades);
	return $unserialized;
}

/**
 * Returns the version of the upgrade filename.
 *
 * @param string $filename The upgrade filename. No full path.
 * @return int|false
 * @since 1.8
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
 * @param string $upgrade_path The up
 * @return array|false
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
		// make sure this is a wellformed upgrade.
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
 * Get the current version information
 *
 * @param bool $humanreadable Whether to return a human readable version (default: false)
 *
 * @return string|false Depending on success
 */
function get_version($humanreadable = false) {
	global $CONFIG;

	if (isset($CONFIG->path)) {
		if (include($CONFIG->path . "version.php")) {
			return (!$humanreadable) ? $version : $release;
		}
	}

	return FALSE;
}

/**
 * Checks if any upgrades need to be run.
 *
 * @param null|array $upgrade_files      Optional upgrade files
 * @param null|array $processed_upgrades Optional processed upgrades
 *
 * @return array()
 */
function elgg_get_unprocessed_upgrades($upgrade_files = null, $processed_upgrades = null) {
	if ($upgrade_files === null) {
		$upgrade_files = elgg_get_upgrade_files();
	}

	if ($processed_upgrades === null) {
		$processed_upgrades = unserialize(datalist_get('processed_upgrades'));
		if (!is_array($processed_upgrades)) {
			$processed_upgrades = array();
		}
	}

	$unprocessed = array_diff($upgrade_files, $processed_upgrades);
	return $unprocessed;
}

/**
 * Determines whether or not the database needs to be upgraded.
 *
 * @return true|false Depending on whether or not the db version matches the code version
 */
function version_upgrade_check() {
	$dbversion = (int) datalist_get('version');
	$version = get_version();

	if ($version > $dbversion) {
		return TRUE;
	}

	return FALSE;
}

/**
 * Upgrades Elgg Database and code
 *
 * @return bool
 *
 */
function version_upgrade() {
	// It's possible large upgrades could exceed the max execution time.
	set_time_limit(0);

	$dbversion = (int) datalist_get('version');

	// No version number? Oh snap...this is an upgrade from a clean installation < 1.7.
	// Run all upgrades without error reporting and hope for the best.
	// See http://trac.elgg.org/elgg/ticket/1432 for more.
	$quiet = !$dbversion;

	// Note: Database upgrades are deprecated as of 1.8.  Use code upgrades.  See #1433
	if (db_upgrade($dbversion, '', $quiet)) {
		system_message(elgg_echo('upgrade:db'));
	}

	if (upgrade_code($dbversion, $quiet)) {
		system_message(elgg_echo('upgrade:core'));

		// Now we trigger an event to give the option for plugins to do something
		$upgrade_details = new stdClass;
		$upgrade_details->from = $dbversion;
		$upgrade_details->to = get_version();

		elgg_trigger_event('upgrade', 'upgrade', $upgrade_details);

		return true;
	}

	return false;
}

/**
 * Boot straps into 1.8 upgrade system from 1.7
 *
 * This runs all the 1.7 upgrades, then sets the processed_upgrades to all existing 1.7 upgrades.
 * Control is then passed back to the main upgrade function which detects and runs the
 * 1.8 upgrades, regardless of filename convention.
 *
 * @return bool
 */
function elgg_upgrade_bootstrap_17_to_18() {
	$db_version = (int) datalist_get('version');

	// the 1.8 upgrades before the upgrade system change that are interspersed with 1.7 upgrades.
	$upgrades_18 = array(
		'2010111501.php',
		'2010121601.php',
		'2010121602.php',
		'2010121701.php',
		'2010123101.php',
		'2011010101.php',
	);

	$upgrades_17 = array();
	$upgrade_files = elgg_get_upgrade_files();
	$processed_upgrades = array();

	foreach ($upgrade_files as $upgrade_file) {
		// ignore if not in 1.7 format or if it's a 1.8 upgrade
		if (in_array($upgrade_file, $upgrades_18) || !preg_match("/[0-9]{10}\.php/", $upgrade_file)) {
			continue;
		}

		$upgrade_version = elgg_get_upgrade_file_version($upgrade_file);

		// this has already been run in a previous 1.7.X -> 1.7.X upgrade
		if ($upgrade_version < $db_version) {
			$processed_upgrades[] = $upgrade_file;
		}
	}

	return elgg_set_processed_upgrades($processed_upgrades);
}
