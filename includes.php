<?php

	// error_reporting(E_ERROR | E_WARNING | E_PARSE);

	// ELGG system includes
	
	// System constants: set values as necessary
	// Supply your values within the second set of speech marks in the pair
	// i.e., define("system constant name", "your value");
	
		// Name of the site (eg Elgg, Apcala, University of Bogton's Learning Landscape, etc)
			define("sitename", "");
		// External URL to the site (eg http://elgg.bogton.edu/)
		// NB: **MUST** have a final slash at the end
			define("url", "");
		// Physical path to the files (eg /home/elggserver/httpdocs/)
		// NB: **MUST** have a final slash at the end
			define("path", "");
		// Email address of the master admin (eg elgg-admin@bogton.edu)
			define("email", "");
		// Country code to set language to if you have gettext installed
		// To include new languages, save their compiled .mo gettext
		// file into languages/country code/LC_MESSAGES/
		// (the file within this folder must be called elgg.mo)
		// An Elgg gettext template is included as /elgg.pot
			define("locale", "en_EN");
			
	// Database config:
	
		// Database server (eg localhost)
			define("db_server", "");
		// Database username
			define("db_user", "");
		// Database password
			define("db_pass", "");
		// Database name
			define("db_name", "");
					
	// Load required system files: do not edit this line.
		require("includes_system.php");
		
	/***************************************************************************
	*	INSERT PLUGINS HERE
	*	Eventually this should be replaced with plugin autodiscovery
	****************************************************************************/
		
	// XMLRPC
	//	@include(path . "units/rpc/main.php");
	
	// Atom
	//	@include(path . "units/atom/main.php");
	
	/***************************************************************************
	*	CONTENT MODULES
	*	This should make languages easier, although some kind of
	*	selection process will be required
	****************************************************************************/
		
	// General
		@include(path . "content/general/main.php");
	// Main index
		@include(path . "content/mainindex/main.php");
	// User-related
		@include(path . "content/users/main.php");
	
	/***************************************************************************
	*	HELP MODULES
	****************************************************************************/
		
	// Include main
		@include(path . "help/mainindex/main.php");
		
	/***************************************************************************
	*	START-OF-PAGE RUNNING
	****************************************************************************/
	
		run("init");
		
?>