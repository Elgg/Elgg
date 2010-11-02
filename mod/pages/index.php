<?php
/**
 * Elgg Pages list
 *
 * @package ElggPages
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

global $CONFIG;

// Add menus
$owner = elgg_get_page_owner();
if (!($owner instanceof ElggGroup)) {
		add_submenu_item(sprintf(elgg_echo("pages:user"), elgg_get_page_owner()->name), "pg/pages/owned/" . elgg_get_page_owner()->username, 'pageslinksgeneral');
		add_submenu_item(elgg_echo('pages:all'), "mod/pages/world.php", 'pageslinksgeneral');
}
	if (($owner instanceof ElggEntity) && (can_write_to_container(0,$owner->guid))){
		add_submenu_item(elgg_echo('pages:new'), "pg/pages/new/?container_guid=" . elgg_get_page_owner_guid(), 'pagesactions');
		add_submenu_item(elgg_echo('pages:welcome'), "pg/pages/welcome/" . $owner->username, 'pagesactions');
	}

// access check for closed groups
group_gatekeeper();

$limit = get_input("limit", 10);
$offset = get_input("offset", 0);

if($owner instanceof ElggGroup){
	$title = sprintf(elgg_echo("pages:group"),$owner->name);
}else{
	$title = sprintf(elgg_echo("pages:user"),$owner->name);
}


// Get objects
$context = get_context();

set_context('search');

$objects = elgg_list_entities(array('types' => 'object', 'subtypes' => 'page_top', 'container_guid' => elgg_get_page_owner_guid(), 'limit' => $limit, 'offset' => $offset, 'full_view' => FALSE));

set_context($context);

//get the owners latest welcome message
$welcome_message = elgg_get_entities(array('types' => 'object', 'subtypes' => 'pages_welcome', 'container_guid' => $owner->guid, 'limit' => 1));

$body = elgg_view_title($title);
$body .= elgg_view("pages/welcome", array('entity' => $welcome_message));
$body .= $objects;
$body = elgg_view_layout('one_column_with_sidebar', $body);

// Finally draw the page
page_draw($title, $body);
