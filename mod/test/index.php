<?php

	require_once("../../engine/start.php");
	
	global $CONFIG;
	
	//var_export($CONFIG);

	$body = elgg_view("testplugin/pageshell");
	page_draw("Test plugin",$body);
	
?>