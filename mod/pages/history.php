<?php
/**
 * Elgg Pages
 *
 * @package ElggPages
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
gatekeeper();

$page_guid = get_input('page_guid');

$pages = get_entity($page_guid);
if ($pages->container_guid) {
	set_page_owner($pages->container_guid);
} else {
	set_page_owner($pages->owner_guid);
}

if (is_callable('group_gatekeeper')) group_gatekeeper();

$limit = (int)get_input('limit', 20);
$offset = (int)get_input('offset');

$page_guid = get_input('page_guid');
$pages = get_entity($page_guid);

add_submenu_item(sprintf(elgg_echo("pages:user"), page_owner_entity()->name), $CONFIG->url . "pg/pages/owned/" . page_owner_entity()->username, 'pageslinksgeneral');

$title = $pages->title . ": " . elgg_echo("pages:history");
$content = elgg_view_title($title);
$content.= list_annotations($page_guid, 'page', $limit, false);

pages_set_navigation_parent($pages);
$sidebar = elgg_view('pages/sidebar/tree');

$body = elgg_view_layout('one_column_with_sidebar', $content, $sidebar);

page_draw($title, $body);