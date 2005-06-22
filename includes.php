<?php

	ini_set("display_errors", "1");
	error_reporting(E_ERROR | E_WARNING | E_PARSE);

	// ELGG system includes
	
	// System constants: set values as necessary
	// Supply your values within the second set of speech marks in the pair
	// i.e., define("system constant name", "your value");
	
		// Name of the site (eg Elgg, Apcala, University of Bogton's Learning Landscape, etc)
			define("sitename", "Ben's test server");
		// External URL to the site (eg http://elgg.bogton.edu/)
		// NB: **MUST** have a final slash at the end
			define("url", "http://localhost/");
		// Physical path to the files (eg /home/elggserver/httpdocs/)
		// NB: **MUST** have a final slash at the end
			define("path", "c:\Documents and Settings\Ben\My Documents\code\public_html\\");
		// Email address of the master admin (eg elgg-admin@bogton.edu)
			define("email", "ben@elgg.net");

	// Database config:
	
		// Database server (eg localhost)
			define("db_server", "localhost");
		// Database username
			define("db_user", "root");
		// Database password
			define("db_pass", "");
		// Database name
			define("db_name", "elgg");
					
	// Load required system files: do not edit this line.
		require("includes_system.php");
		
	/***************************************************************************
	*	INSERT PLUGINS HERE
	*	Eventually this should be replaced with plugin autodiscovery
	****************************************************************************/
		
	// XMLRPC
	//	@include(path . "units/rpc/main.php");
	
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
	*	START-OF-PAGE RUNNING
	****************************************************************************/
	
		run("init");
		
?>