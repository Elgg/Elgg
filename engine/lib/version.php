<?php
/**
 * Elgg version library.
 * Contains code for handling versioning and upgrades.
 *
 * @package Elgg
 * @subpackage Core
 * @link http://elgg.org/
 */

/**
 * Run any php upgrade scripts which are required
 *
 * @param int $version Version upgrading from.
 * @param bool $quiet Suppress errors.  Don't use this.
 */
function upgrade_code($version, $quiet = FALSE) {
	global $CONFIG;

	// Elgg and its database must be installed to upgrade it!
	if (!is_db_installed() || !is_installed()) {
		return FALSE;
	}

	$version = (int) $version;

	if ($handle = opendir($CONFIG->path . 'engine/lib/upgrades/')) {
		$upgrades = array();

		while ($updatefile = readdir($handle)) {
			// Look for upgrades and add to upgrades list
			if (!is_dir($CONFIG->path . 'engine/lib/upgrades/' . $updatefile)) {
				if (preg_match('/^([0-9]{10})\.(php)$/', $updatefile, $matches)) {
					$core_version = (int) $matches[1];
					if ($core_version > $version) {
						$upgrades[] = $updatefile;
					}
				}
			}
		}

		// Sort and execute
		asort($upgrades);

		if (sizeof($upgrades) > 0) {
			foreach($upgrades as $upgrade) {
				// hide all errors.
				if ($quiet) {
					// hide include errors as well as any exceptions that might happen
					try {
						if (!@include($CONFIG->path . 'engine/lib/upgrades/' . $upgrade)) {
							error_log($e->getmessage());
						}
					} catch (Exception $e) {
						error_log($e->getmessage());
					}
				} else {
					include($CONFIG->path . 'engine/lib/upgrades/' . $upgrade);
				}
			}
		}

		return TRUE;
	}

	return FALSE;
}

/**
 * Get the current version information
 *
 * @param true|false $humanreadable Whether to return a human readable version (default: false)
 * @return string|false Depending on success
 */
function get_version($humanreadable = false) {
	global $CONFIG;

	if (include($CONFIG->path . "version.php")) {
		return (!$humanreadable) ? $version : $release;
	}

	return FALSE;
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
 * Upgrades Elgg
 *
 */
function version_upgrade() {
	$dbversion = (int) datalist_get('version');

	// No version number? Oh snap...this is an upgrade from a clean installation < 1.7.
	// Run all upgrades without error reporting and hope for the best.
	// See http://trac.elgg.org/elgg/ticket/1432 for more.
	$quiet = !$dbversion;

	// Upgrade database
	if (db_upgrade($dbversion, '', $quiet)) {
		system_message(elgg_echo('upgrade:db'));
	}

	// Upgrade core
	if (upgrade_code($dbversion, $quiet)) {
		system_message(elgg_echo('upgrade:core'));
	}

	// Now we trigger an event to give the option for plugins to do something
	$upgrade_details = new stdClass;
	$upgrade_details->from = $dbversion;
	$upgrade_details->to = get_version();

	trigger_elgg_event('upgrade', 'upgrade', $upgrade_details);

	// Update the version
	datalist_set('version', get_version());
}
