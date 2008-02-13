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

		if (!@include_once(dirname(__FILE__) . "/lib/elgglib.php")) {		// Main Elgg library
			echo "Error in installation: could not load the main Elgg library.";
			exit;
		}
		
	/**
	 * Load the system settings
	 */
		
		@include_once(dirname(__FILE__) . "/settings.php"); 		// Global settings
		
	/**
	 * If there are basic issues with the way the installation is formed, don't bother trying
	 * to load any more files
	 */
		
		if (sanitised()) {	// Begin portion for sanitised installs only
	
		/**
		 * Load and initialise the database
		 */
	
			if (!@include_once(dirname(__FILE__) . "/lib/database.php"))	// Database connection
				register_error("Could not load the main Elgg database library.");
	
				
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
				if (!@include_once($file))
					register_error("Could not load {$file}");
			}
		
		}	// End portion for sanitised installs only
			
		// Trigger events
			trigger_event('init', 'system');
		
		// If we have load errors, display them
			if ($count = count_messages("errors")) {
				echo elgg_view('pageshell', array(
												'title' => "Elgg isn't ready to run just yet.",
												'body' => elgg_view('messages/errors/list',array('object' => system_messages(null, "errors")))
											));
				exit;
			}
		
?>