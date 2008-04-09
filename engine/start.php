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

		if (!@include_once(dirname(__FILE__) . "/lib/exceptions.php")) {		// Exceptions 
			echo "Error in installation: could not load the Exceptions library.";
			exit;
		}

		if (!@include_once(dirname(__FILE__) . "/lib/elgglib.php")) {		// Main Elgg library
			throw new InstallationException("Elgg could not load its main library.");
		}
		
		if (!@include_once(dirname(__FILE__) . "/lib/export.php")) {		// Export library
			echo "Error in installation: could not load the Export library.";
			exit;
		}

		
	/**
	 * Establish handlers
	 */
		
	// Register the error handler
		set_error_handler('__elgg_php_error_handler');
		set_exception_handler('__elgg_php_exception_handler');
		
	/**
	 * If there are basic issues with the way the installation is formed, don't bother trying
	 * to load any more files
	 */
		
		if (sanitised()) {	// Begin portion for sanitised installs only
	
		 /**
		 * Load the system settings
		 */
			
			if (!@include_once(dirname(__FILE__) . "/settings.php"))  		// Global settings
				throw new InstallationException("Elgg could not load the settings file.");
				
		/**
		 * Load and initialise the database
		 */
	
			if (!@include_once(dirname(__FILE__) . "/lib/database.php"))	// Database connection
				throw new InstallationException("Elgg could not load the main Elgg database library.");
				
		/**
		 * Load the remaining libraries from /lib/ in alphabetical order,
		 * except for a few exceptions
		 */
			
			if (!@include_once(dirname(__FILE__) . "/lib/actions.php")) {
				throw new InstallationException("Elgg could not load the Actions library");
			}	

			if (!@include_once(dirname(__FILE__) . "/lib/sessions.php")) {
				throw new InstallationException("Elgg could not load the Sessions library");
			}	

		// We don't want to load or reload these files
	
			$file_exceptions = array(
										'.','..',
										'.svn',
										'CVS','cvs',
										'settings.php','settings.example.php','exceptions.php','elgglib.php','database.php','actions.php','sessions.php'
									);
	
		// Get the list of files to include, and alphabetically sort them
	
			$files = get_library_files(dirname(__FILE__) . "/lib",$file_exceptions);
			asort($files);
	
		// Include them
		
			foreach($files as $file) {
				if (!@include_once($file))
					throw new InstallationException("Could not load {$file}");
			}
			
		// Set default config
			set_default_config();
			
		// Load plugins
			load_plugins();
		
		} else {	// End portion for sanitised installs only
			
			throw new InstallationException("Once you've corrected any configuration issues, press reload to try again.");
			
		}
		
		// Autodetect some default configuration settings
			set_default_config();
		
		// Trigger events
			trigger_event('boot', 'system');
			
		// Forward if we haven't been installed
			if ((!is_installed() || !is_db_installed()) && !substr_count($_SERVER["PHP_SELF"],"install.php")) {
				forward("install.php");
			}
			
		// Trigger events
			if (!substr_count($_SERVER["PHP_SELF"],"install.php")) {
				trigger_event('init', 'system');
			}

?>