<?php

	// Plug-in engine intialisation routines
	
	// Global log arrays
		global $log;
		global $errorlog;
		global $actionlog;
		$log = array();
		$errorlog = array();
		$actionlog = array();
	
	// Message arrays
		global $messages;
		$messages = array();
		
	// Add the site root to the metatags
		global $metatags;
		// $metatags .= " 	<base href=\"".url."\" />";

?>