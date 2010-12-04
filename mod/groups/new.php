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
	
	$body = elgg_view_layout('one_column_with_sidebar', array('content' => $area1 . $area2));
	
	echo elgg_view_page($title, $body);
?>