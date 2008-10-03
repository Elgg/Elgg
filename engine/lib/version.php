<?php

	/**
	 * Elgg version library.
	 * Contains code for handling versioning and upgrades.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Get the current version information
	 *
	 * @param true|false $humanreadable Whether to return a human readable version (default: false)
	 * @return string|false Depending on success
	 */
		function get_version($humanreadable = false) {
			
			global $CONFIG;
			if (@include($CONFIG->path . "version.php")) {
				if (!$humanreadable) return $version;
				return $release;
			}
			
			return false;
			
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
				return true;
			}
			return false;
			
		}
		
	/**
	 * Upgrades Elgg
	 *
	 */
		function version_upgrade() {
			
			$dbversion = (int) datalist_get('version');
			db_upgrade($dbversion);
			datalist_set('version', get_version());
			system_message(elgg_echo('upgrade:db'));
			//forward();
			//exit;
			
		}
		
	/**
	 * Runs an upgrade check on boot.
	 *
	 */
		function version_boot() {
			
			if (!is_installed()) return false;
			
			if (version_upgrade_check()) {
				version_upgrade();
			}
			
		}
		
	// Register the boot handler for version
		register_elgg_event_handler("boot","system","version_boot");

?>