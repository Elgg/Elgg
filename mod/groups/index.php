<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	
	$title = elgg_echo("groups:owned");

	// Get objects
	$area1 = elgg_view('page_elements/content_header', array('context' => "everyone", 'type' => 'groups', 'new_link' => "pg/groups/new"));
	
	set_context('search');
	$objects = elgg_list_entities(array('types' => 'group', 'owner_guid' => elgg_get_page_owner_guid(), 'limit' => $limit, 'offset' => $offset, 'full_view' => FALSE));
	set_context('groups');
	
	$area1 .= $objects;
	$body = elgg_view_layout('one_column_with_sidebar', $area1);
	
	// Finally draw the page
	page_draw($title, $body);
?>
