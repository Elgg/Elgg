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

		require_once(dirname(__FILE__) . "/lib/elgglib.php");	// Elgg core functions
		require_once(dirname(__FILE__) . "/lib/database.php");	// Database connection
		include(dirname(__FILE__) . "/settings.php");		// Global settings

	/**
	 * Load the remaining libraries from /lib/ in alphabetical order,
	 * except for a few exceptions
	 */
		
	// We don't want to load or reload these files

		$file_exceptions = array(
									'.','..',
									'.svn',
									'settings.php','settings.example.php','elgglib.php','database.php'
								);

	// Get the list of files to include, and alphabetically sort them

		$files = get_library_files(dirname(__FILE__) . "/lib",$file_exceptions);
		asort($files);

	// Include them
	
		foreach($files as $file) {
			include($file);
		}
		
?>