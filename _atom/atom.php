<?php

	/**
	 * atom entry point for Elgg
	 */
    	 
	include_once( "../includes.php" );
	
	run("profile:init");
	
    include_once( path . "units/atom/isotope/atom.php" );
?>
