<?php
	include("../includes.php");
	
	$event_id = (int) $_REQUEST["event_id"];
	
	run("calendar:export:event", array($event_id));
?>
