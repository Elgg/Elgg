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
	 * @param unknown_type $version
	 */
	function upgrade_code($version) 
	{
		global $CONFIG;
		
		// Elgg and its database must be installed to upgrade it!
        if (!is_db_installed() || !is_installed()) return false;
		
		$version = (int) $version;
        	
        if ($handle = opendir($CONFIG->path . 'engine/lib/upgrades/')) {
        		
        	$upgrades = array();
        	
        	while ($updatefile = readdir($handle)) {
        		
        		// Look for upgrades and add to upgrades list
        		if (!is_dir($CONFIG->path . 'engine/lib/upgrades/' . $updatefile)) {
        			if (preg_match('/([0-9]*)\.php/',$updatefile,$matches)) {
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
        			try {
        				@include($CONFIG->path . 'engine/lib/upgrades/' . $upgrade);
        			} catch (Exception $e) {
        				error_log($e->getmessage());
        			}	
        			
        		}
        	}

        	return true;
        }
        	
        return false;
	}

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
			
			// Upgrade database
			db_upgrade($dbversion);
			system_message(elgg_echo('upgrade:db'));
			
			// Upgrade core
			if (upgrade_code($dbversion))
				system_message(elgg_echo('upgrade:core'));
				
			// Now we trigger an event to give the option for plugins to do something
			$upgrade_details = stdClass;
			$upgrade_details->from = $dbversion;
			$upgrade_details->to = get_version();
			
			trigger_elgg_event('upgrade', 'upgrade', $upgrade_details);
				
			// Update the version
			datalist_set('version', get_version());
			
		}
		
?>