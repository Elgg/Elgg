<?php
	require("../includes.php");
	
	
	run("calendar:init");
	run("profile:init");
	run("friends:init");
	
	define("context", "calendar");
	
	
	global $calendar_id;
	
		
	$title = run("profile:display:name") . " :: " . gettext("Calendar");
		
	$body = run("calendar:add_event", array($calendar_id, isset($_GET["reset"])));
	
	
	$body = run("templates:draw", array(
					'context' => 'contentholder',
					'title' => $title,
					'body' => $body
				)
				);
	
	echo run("templates:draw:page", array(
				$title, $body
			)
			);

	
?>
