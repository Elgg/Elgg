<?php

	/*
	*	MySQL database plug-in
	*/

	// Configuration
		require("conf.php");
		
	// Library functions
		require("library.php");
		
	// On initialisation, run the connect script
		$function['init'][] = path . "units/db/function_connect.php";
	
?>