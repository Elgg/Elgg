<?php

/**
 * Elgg blog add entry page
 */

// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
gatekeeper();
		
// Get the current page's owner
$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($_SESSION['guid']);
}
if ($page_owner instanceof ElggGroup)
	$container = $page_owner->guid;
		
//set breadcrumbs
//$area1 = elgg_view('elggcampus_layout/breadcrumbs_general', array('object_type' => 'blog'));
	
// Get the form
$area1 .= elgg_view("blog/forms/edit", array('container_guid' => $container));
		
// Display page
page_draw(elgg_echo('blog:addpost'),elgg_view_layout("one_column", $area1));