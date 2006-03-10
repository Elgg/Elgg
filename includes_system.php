<?php

	// ELGG system includes

	/***************************************************************************
	*	INSERT SYSTEM UNITS HERE
	*	You should ideally not edit this file.
	****************************************************************************/




	// Sanity checks - conditions under which Elgg will refuse to run
	// TODO - this'll no doubt want polishing and gettexting :) - Sven
	
	$diemessages = array();
	
	if (!defined("path") || (substr(path, -1) != "/")) {
		$diemessages[] = "Configuration problem: The 'path' setting in includes.php must end with a forward slash (/).";
	}
	if (!defined("url") || (substr(url, -1) != "/")) {
		$diemessages[] = "Configuration problem: The 'url' setting in includes.php must end with a forward slash (/).";
	}
	if (ini_get('register_globals')) {
		// this shouldn't be needed due to the htaccess file, but just in case...
		$diemessages[] = "
			Configuration problem: The PHP setting 'register_globals', which is a huge security risk, is turned on.
			There should be a line in the .htaccess file as follows: <code>php_flag register_globals off</code>
		";
	}
	if (!ini_get('magic_quotes_gpc')) {
		// this shouldn't be needed due to the htaccess file, but just in case...
		$diemessages[] = "
			Configuration problem: The PHP setting 'magic_quotes_gpc' is turned off.
			There should be a line in the .htaccess file as follows: <code>php_flag magic_quotes_gpc on</code>
		";
	}
	if (!function_exists('mysql_query')) {
		// people have been having a spot of trouble installing elgg without the mysql php module...
		$diemessages[] = "
			Installation problem: Can't find the PHP MySQL module.
			Even with PHP and MySQL installed, sometimes the module to connect them is missing.
			Please check your PHP installation.
		";
	}
	
	
	if (count($diemessages)) {
		$diebody  = '<html><body><h1>Error - Elgg cannot run</h1><ul>';
		$diebody .= '<li>' . implode("</li><li>", $diemessages) . '</li>';
		$diebody .= '</ul><p>Please read the INSTALL file for more information.</p></body></html>';
		die($diebody);
	} else {
		unset($diemessages);
	}
	
	
	
	
	if (!defined("ELGG_DEBUG")) { 
		define("ELGG_DEBUG", false);
	}
	if (ELGG_DEBUG === true) {
		error_reporting(E_ALL ^ E_NOTICE);
		ini_set("display_errors", true);
	}
	
	
	
	
	// Plug-in engine (must be loaded first)
		require(path . "units/engine/main.php");
	// Language / internationalisation
		require(path . "units/gettext/main.php");
	// Database
		require(path . "units/db/main.php");
	// Display
		require(path . "units/display/main.php");
	// Users
		require(path . "units/users/main.php");
	// Templates
		require(path . "units/templates/main.php");
	// Edit permissions
		require(path . "units/permissions/main.php");
		
	// User icons
		include(path . "units/icons/main.php");
	// Profiles
		include(path . "units/profile/main.php");
		
	// Weblog
		include(path . "units/weblogs/main.php");
	
	// File repository
		include(path . "units/files/main.php");
				
	// Communities
		require(path . "units/communities/main.php");
		
	// Friends
		include(path . "units/friends/main.php");
	// Friend groups
		include(path . "units/groups/main.php");
		
	// 'Your network'
		require(path . "units/network/main.php");
		
	// Search
		require(path . "units/search/main.php");
		
	// Invite-a-friend
		require(path . "units/invite/main.php");
		
	// Admin system
		require(path . "units/admin/main.php");
		
	// XML parsing
		require(path . "units/xml/main.php");
		
	// Your Resources
		require(path . "units/magpie/main.php");
		
?>