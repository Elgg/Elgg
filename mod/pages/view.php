<?php
/**
 * View a single page
 *
 * @package ElggPages
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$page_guid = get_input('page_guid');
elgg_set_context('pages');

if (is_callable('group_gatekeeper')) {
	group_gatekeeper();
}

$pages = get_entity($page_guid);
if (!$pages) {
	forward();
}

$container = $pages->container_guid;

if ($container) {
	set_page_owner($container);
} else {
	set_page_owner($pages->owner_guid);
}

global $CONFIG;

if ($pages->canEdit()) {
	add_submenu_item(elgg_echo('pages:newchild'), "pg/pages/new/?parent_guid={$pages->getGUID()}&container_guid=" . elgg_get_page_owner_guid(), 'pagesactions');
	$delete_url = elgg_add_action_tokens_to_url("action/pages/delete?page={$pages->getGUID()}");
	add_submenu_item(elgg_echo('pages:delete'), $delete_url, 'pagesactions', true);
}

//if the page has a parent, get it
if ($parent_page = get_entity(get_input("page_guid"))) {
	$parent = $parent_page;
}

$title = $pages->title;

// Breadcrumbs
$body = elgg_view('pages/breadcrumbs', array('page_owner' => elgg_get_page_owner(), 'parent' => $parent));

$body .= elgg_view_title($pages->title);
$body .= elgg_view_entity($pages, true);

//add comments
$body .= elgg_view_comments($pages);

pages_set_navigation_parent($pages);
$sidebar = elgg_view('pages/sidebar/tree');

$params = array(
	'content' => $body,
	'sidebar' => $sidebar
);
$body = elgg_view_layout('one_column_with_sidebar', $params);

echo elgg_view_page($title, $body);