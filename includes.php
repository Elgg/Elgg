<?php

	// ELGG system includes
	
	// System constants: set as necessary
	
		// Name of the site (eg Elgg, Apcala, University of Bogton's Learning Landscape, etc)
			define("sitename", "");
		// External URL to the site (eg http://elgg.bogton.edu/)
			define("url", "");
		// Physical path to the files (eg /home/elggserver/httpdocs/)
			define("path", "");
		// Email address of the master admin (eg elgg-admin@bogton.edu)
			define("email", "");

	// Database config:
	
		// Database server
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
	
	// Invite-a-friend
		@include(path . "units/invite/main.php");
	
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