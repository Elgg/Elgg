<?php
/**
 * View a single page
 *
 * @package ElggPages
 */

$page_guid = get_input('guid');
$page = get_entity($page_guid);
if (!$page) {
	forward();
}

elgg_set_page_owner_guid($page->getContainerGUID());

group_gatekeeper();

$container = elgg_get_page_owner();
if (!$container) {
}

$title = $page->title;

elgg_push_breadcrumb($container->name, $container->getURL());
elgg_push_breadcrumb($title);

$content = elgg_view_entity($page, true);
$content .= elgg_view_comments($page);

$sidebar = elgg_view('pages/sidebar/tree', array('page' => $page));

$buttons = '';
if ($page->canEdit()) {
	$url = "pg/pages/add/$page->guid";
	$buttons = elgg_view('output/url', array(
			'text' => elgg_echo('pages:newchild'),
			'href' => $url,
			'class' => 'elgg-action-button',
		));
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'buttons' => $buttons,
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
