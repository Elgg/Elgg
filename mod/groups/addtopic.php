<?php
/**
 * Elgg Groups add a forum topic page
 */

// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
group_gatekeeper();
		
$page_owner = set_page_owner((int) get_input('group_guid'));
		
if (!(elgg_get_page_owner() instanceof ElggGroup)) forward();
		
// sort the display
$area2 = elgg_view("forms/forums/addtopic");
$body = elgg_view_layout('one_column_with_sidebar', $area1.$area2);
		
// Display page
page_draw(elgg_echo('groups:addtopic'),$body);