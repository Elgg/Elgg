<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 */

	gatekeeper();

	set_page_owner(get_loggedin_userid());

	// Render the file upload page
	$title = elgg_echo("groups:new");
	$area2 = elgg_view_title($title);
	$area2 .= elgg_view("forms/groups/edit");
	
	$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
	
	page_draw($title, $body);
?>