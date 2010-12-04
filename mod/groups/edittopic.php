<?php
/**
 * Elgg Groups edit a forum topic page
 */

// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
gatekeeper();
		
get_input('group');
$page_owner = set_page_owner((int)get_input('group'));
	
// check the user is a member of the group
if (!(elgg_get_page_owner() instanceof ElggGroup)) forward();
	
//get the topic
$topic = get_entity((int) get_input('topic'));
		
// sort the display
$area2 = elgg_view("forms/forums/edittopic", array('entity' => $topic));
$body = elgg_view_layout('one_column_with_sidebar', array('content' => $area2));
		
// Display page
echo elgg_view_page(elgg_echo('groups:edittopic'),$body);