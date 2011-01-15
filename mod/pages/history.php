<?php
/**
 * Elgg Pages
 *
 * @package ElggPages
 */

$page_guid = get_input('guid');

$page = get_entity($page_guid);
if (!$page) {

}

$container = $page->getContainerEntity();
if (!$container) {

}

elgg_set_page_owner_guid($container->getGUID());

elgg_push_breadcrumb($page->title, $page->getURL());
elgg_push_breadcrumb(elgg_echo('pages:history'));

$title = $page->title . ": " . elgg_echo('pages:history');

$content = list_annotations($page_guid, 'page', 20, false);

pages_set_navigation_parent($page);
$sidebar = elgg_view('pages/sidebar/tree');

$body = elgg_view_layout('content', array(
	'filter' => '',
	'buttons' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
