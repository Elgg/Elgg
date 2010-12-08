<?php

	/**
	 * Elgg groups 'member of' page
	 * 
	 * @package ElggGroups
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	gatekeeper();
	group_gatekeeper();
	
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	
	if (elgg_get_page_owner_guid() == get_loggedin_userid()) {
		$title = elgg_echo("groups:yours");
	} else $title = elgg_echo("groups:owned");

	// Get objects
	$area2 = elgg_view('page/elements/content_header', array('context' => "mine", 'type' => 'groups', 'new_link' => "pg/groups/new"));
	
	elgg_push_context('search');
	// offset is grabbed in the list_entities_from_relationship() function
	$objects = list_entities_from_relationship('member',page_owner(),false,'group','',0, $limit,false, false);
	elgg_pop_context();
	
	$area2 .= $objects;
	$body = elgg_view_layout('one_column_with_sidebar', array('content' => $area1. $area2));
	
	// Finally draw the page
	echo elgg_view_page($title, $body);
?>
