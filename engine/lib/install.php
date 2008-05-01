<?php

	/**
	 * Elgg installation
	 * Various functions to assist with installing and upgrading the system
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Returns whether or not the database has been installed
	 *
	 * @return true|false Whether the database has been installed
	 */
		function is_db_installed() {
			
			$tables = get_db_tables();
			if (!$tables) {
				return false;
			}
			return true;
			
		}
		
	/**
	 * Returns whether or not other settings have been set
	 *
	 * @return true|false Whether or not the rest of the installation has been followed through with
	 */
		function is_installed() {
			
			global $CONFIG;
			if (!empty($CONFIG->path))
				return true;
				
			return false;
			
		}

	/**
	 * Function that gets run once, when the system is first installed
	 *
	 */
		function install_prerequisites() {
			
			// Load existing config
				global $CONFIG;
			
			// Create a new Elgg site
				$site = new ElggSite();
				$site->save();
				
			// Set datalist alerting us to the fact that the default site is this one
				datalist_set('default_site',$site->getGUID());
			
		}
		
	/**
	 * Functions to be run at install init-time.
	 *
	 */
		function install_init() {

			// Run the install_prerequisites function just once
				run_function_once("install_prerequisites");

		}
		
	// Make sure install_boot gets called on system book
		register_event_handler('init','system','install_init',1);
		
?>