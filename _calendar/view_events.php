<?php
	
	require("../includes.php");
	
	run("calendar:init");
	run("profile:init");
	run("friends:init");
	
	define("context", "calendar");
	
	//whose calendar are we looking at, defined in calendar:init
	global $calendar_id;
	
	$title = run("profile:display:name") . " :: " . gettext("Calendar");
	$body = null;
	
	if (!isset($_REQUEST["friend_id"]) && !isset($_REQUEST["event_id"]) && !isset($_REQUEST["community_id"])) {
		$selected_month = (int) $_REQUEST["selected_month"];
		$selected_year = (int) $_REQUEST["selected_year"];
		$selected_day = (int) $_REQUEST["selected_day"];
		$context = $_REQUEST["context"];
		
		$body = run("calendar:blog:view", array($selected_month, $selected_year, $selected_day, $context));
		
	}else if(isset($_REQUEST["friend_id"])){
		$body = run("calendar:blog:view", array($_REQUEST["friend_id"], "friends"));
	}else if(isset($_REQUEST["event_id"])){
		$body = run("calendar:blog:view", array($_REQUEST["event_id"], "tags"));
	}else if(isset($_REQUEST["community_id"])){
		$body = run("calendar:blog:view", array($_REQUEST["community_id"], "communities"));
	}
	
	
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
	
	unset($_SESSION["messages"]);
?>
