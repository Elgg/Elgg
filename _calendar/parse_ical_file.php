<?php
	include("../includes.php");
	
	run("calendar:init");
	run("profile:init");
	
	global $calendar_id;
	
	
	$run_result = run("calendar:ical_parser", array($_FILES["new_file"]["tmp_name"]));
	
	foreach($run_result as $key => $value){
		db_query("INSERT INTO event(owner, title, description, access, location, date_start, date_end) " .
				"VALUES({$calendar_id}, '{$value['Summary']}', '{$value['description']}', 'user{$_SESSION['userid']}', " .
				"'{$value['Location']}', {$value['StartTime']}, {$value['EndTime']})");
	}
	$username = run("profile:display:name");
	
	$_SESSION["messages"][] = "Your calendar has been imported successfully";
	header("Location: " . url . "{$username}/calendar/import");
?>
