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
			return datalist_get('installed');
			
		}
		
		/**
		 * Copy and create a new settings.php from settings.example.php, substituting the variables in
		 * $vars where appropriate.
		 * 
		 * $vars is an associate array of $key => $value, where $key is the variable text you wish to substitute (eg
		 * CONFIG_DBNAME will replace {{CONFIG_DBNAME}} in the settings file.
		 *
		 * @param array $vars The array of vars
		 * @param string $in_file Optional input file (if not settings.example.php)
		 * @return string The file containing substitutions.
		 */
		function create_settings(array $vars, $in_file="engine/settings.example.php")
		{
			$file = file_get_contents($in_file);
			
			if (!$file) return false; 
			
			foreach ($vars as $k => $v)
				$file = str_replace("{{".$k."}}", $v, $file);
			
			return $file;
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