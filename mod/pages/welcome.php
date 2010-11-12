<?php
/**
 * Elgg Pages welcome intro
 * The user or group owner can set an introduction to their wiki pages
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

//get the owners welcome message if it exists
$welcome_message = elgg_get_entities(array('types' => 'object', 'subtypes' => 'pages_welcome', 'container_guid' => $page_owner->getGUID(), 'limit' => 1));
global $CONFIG;
add_submenu_item(elgg_echo("pages:user", array(elgg_get_page_owner()->name)), "pg/pages/owned/" . elgg_get_page_owner()->username, 'pageslinksgeneral');

$title = elgg_echo("pages:welcome");
$area2 .= elgg_view_title($title);
$area2 .= elgg_view("forms/pages/editwelcome", array('entity' => $welcome_message, 'owner' => $page_owner));

$params = array(
	'content' => $area2,
	'sidebar' => $area1
);
$body = elgg_view_layout('one_column_with_sidebar', $params);

echo elgg_view_page($title, $body);