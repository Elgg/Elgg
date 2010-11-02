<?php
/**
 * Create a new Page
 *
 * @package ElggPages
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
gatekeeper();
global $CONFIG;

// Get the current page's owner
if ($container = (int) get_input('container_guid')) {
	set_page_owner($container);
}
$page_owner = elgg_get_page_owner();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = get_loggedin_user();
	set_page_owner($page_owner->getGUID());
}

//if it is a sub page, provide a link back to parent
if(get_input('parent_guid')){
	$parent = get_entity(get_input('parent_guid'));

	// Breadcrumbs
	$area2 .= elgg_view('pages/breadcrumbs', array('page_owner' => $page_owner, 'parent' => $parent, 'add' => true));
	}

	global $CONFIG;
add_submenu_item(sprintf(elgg_echo("pages:user"), elgg_get_page_owner()->name), "pg/pages/owned/" . elgg_get_page_owner()->username, 'pageslinksgeneral');

$title = elgg_echo("pages:new");
$area2 .= elgg_view_title($title);
$area2 .= elgg_view("forms/pages/edit");

$body = elgg_view_layout('one_column_with_sidebar', $area2, $area1);

echo elgg_view_page($title, $body);