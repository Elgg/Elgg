<?php
	require("../includes.php");
	
	
	run("calendar:init");
	run("profile:init");
	run("friends:init");
	
	define("context", "calendar");
	
	
	global $calendar_id;
	
		
	$title = run("profile:display:name") . " :: " . gettext("Calendar");
	$eventid = (int) $_REQUEST["event_id"];
	$body = run("calendar:edit_event", array($eventid));
	
	$body = run("templates:draw", array(
					'context' => 'contentholder',
					'title' => $title,
					'body' => $body
				)
				);
	
	echo run("templates:draw:page", array(
				$title,
				$body
			)
			);
?>
