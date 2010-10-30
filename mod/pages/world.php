<?php
/**
 * Elgg Pages
 *
 * @package ElggPages
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

global $CONFIG;

// Get the current page's owner
$page_owner = elgg_get_page_owner();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = get_loggedin_user();
	set_page_owner(get_loggedin_userid());
}

if (($page_owner instanceof ElggEntity) && ($page_owner->canWriteToContainer())){
	add_submenu_item(elgg_echo('pages:new'), $CONFIG->url . "pg/pages/new/", 'pagesactions');
}

if(isloggedin()) {
	add_submenu_item(sprintf(elgg_echo("pages:user"), elgg_get_page_owner()->name), $CONFIG->url . "pg/pages/owned/" . elgg_get_page_owner()->username, 'pageslinksgeneral');
}

add_submenu_item(elgg_echo('pages:all'),$CONFIG->wwwroot."mod/pages/world.php", 'pageslinksgeneral');

$limit = get_input("limit", 10);
$offset = get_input("offset", 0);

$title = sprintf(elgg_echo("pages:all"),elgg_get_page_owner()->name);

// Get objects
$context = get_context();

set_context('search');

$objects = elgg_list_entities(array('types' => 'object', 'subtypes' => 'page_top', 'limit' => $limit, 'offset' => $offset, 'full_view' => FALSE));

set_context($context);

$body = elgg_view_title($title);
$body .= $objects;
$body = elgg_view_layout('one_column_with_sidebar', $body);

// Finally draw the page
page_draw($title, $body);