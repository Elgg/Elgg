<?php
	require_once("../../engine/start.php");
	
	global $CONFIG;
	
	$guid = get_input("guid");
	$_SESSION['id'] = 2;
	
	// Get the user
	$owner_id = page_owner();
	
	if ($guid)
	{
		echo elgg_view("exporttest/outputxml", array("xml" => export($guid)));
	}
	else if ($action=='import')
	{
		$body = import(get_input('xml'));
		$body .= elgg_view("exporttest/main", array("owner_id" => $owner_id));
		page_draw("Import results",$body);
	}
	else
	{
		$body = elgg_view("exporttest/main", array("owner_id" => $owner_id));
		page_draw("Export a GUID",$body);
	}
?>