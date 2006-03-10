<?php
	require("../includes.php");
	
	run("calendar:init");
	run("profile:init");
	run("friends:init");
	
	define("context", "calendar");
	
	$title = run("profile:display:name") . " :: " . gettext("Calendar");
	
	if(!isset($_REQUEST["selected_month"]) || !isset($_REQUEST["selected_year"])){
		$body = run("calendar:display:monthly", array($_REQUEST["context"]));
		
	}else if(isset($_REQUEST["selected_month"]) && isset($_REQUEST["selected_year"])){
		$body = run("calendar:display:monthly", array($_REQUEST["selected_month"],
													  $_REQUEST["selected_year"], 
													  $_REQUEST["context"]));
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

?>
