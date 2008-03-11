<?php

	require_once("../../engine/start.php");
	
	global $CONFIG;
	
	$body = elgg_view("dashboard/welcome");
	
	echo page_draw("Dashboard", $body);

?>