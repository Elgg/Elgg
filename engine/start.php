<?php

	/**
	 * Elgg engine bootstrapper
	 * Loads the various elements of the Elgg engine
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Load important prerequisites
	 */

		require_once(dirname(__FILE__) . "/settings.php");		// Global settings
		require_once(dirname(__FILE__) . "/lib/elgglib.php");	// Elgg core functions
		require_once(dirname(__FILE__) . "/lib/database.php");	// Database connection
		
	/**
	 * Load the configuration
	 */
		
		global $CONFIG;

	/**
	 * Load the remaining libraries from /lib/ in alphabetical order,
	 * except for a few exceptions
	 */
		
		$file_exceptions = array(
									'.','..',
									'settings.php','settings.example.php','elgglib.php','database.php'
								);
		
		if ($handle = opendir(dirname(__FILE__) . "/lib/")) {
			$files = array();
			while ($file = readdir($handle)) {
				if (!in_array($file,$file_exceptions)) {
					if (!is_dir(dirname(__FILE__) . "/lib/" . $file)) {
						$files[] = dirname(__FILE__) . "/lib/" . $file;
					} else {
						
					}
				}
			}
		}

?>