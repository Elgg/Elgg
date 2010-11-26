<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	
	$title = sprintf(elgg_echo("groups:owned"),page_owner_entity()->name);

	// Get objects
	$area2 = elgg_view_title($title);
	
	set_context('search');
	$objects = elgg_list_entities(array('types' => 'group', 'owner_guid' => page_owner(), 'limit' => $limit, 'offset' => $offset, 'full_view' => FALSE));
	set_context('groups');
	
	$area2 .= $objects;
	$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);
	
	// Finally draw the page
	page_draw($title, $body);
?>