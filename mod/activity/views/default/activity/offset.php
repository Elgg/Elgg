<?php
	/// Extract the offset value
	$offset = $vars['offset'];
	$backset = '-1'; //used to display the forward link
	$add_to_url = $vars['add_to_url'];//used to determine which pane we are looking at - sitewide, friends, own
	$url = "pg/activity/";//set the default url

	if($add_to_url == "friends")
		$url = "pg/activity/" . $_SESSION['user']->username . "/friends/";
	
	if($add_to_url == "own")
		$url = "pg/activity/" . $_SESSION['user']->username;


	if($offset > 19)
		$backset = $offset - 20;//set backset 

	$offset = $offset + 20;//set new offset

	echo "<div style=\"margin:10px 0 0 0;font-size:16px;\">";

	if($backset != '-1')
		echo "<a href=\"{$vars['url']}{$url}?offset={$backset}\">". elgg_echo('activity:forward') . "</a>  ";

	echo "<a href=\"{$vars['url']}{$url}?offset={$offset}\">" . elgg_echo('activity:back') . "</a></div>";
	
?>