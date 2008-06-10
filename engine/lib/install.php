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
	 * Initialisation for installation functions
	 *
	 */
		function install_init() {
			register_action("systemsettings/install",true);			
		}
		
		register_elgg_event_handler("boot","system","install_init");
		
?>