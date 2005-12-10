<?php

	/*
	*	Plug-in engine
	*/

	// Library functions
		require("library.php");
	
	// Initialise variables etc on startup
		$function['init'][] = path . "units/engine/function_init.php";
		
	// "Home" menu buttom
		// $function['menu:main'][] = path . "units/engine/menu_main.php";
	
	// Bottom menu buttons
		$function['menu:bottom'][] = path . "units/engine/menu_bottom.php";
		
?>