<?php
/**
 * View a revision of page
 *
 * @package ElggPages
 */

$id = get_input('id');
$annotation = get_annotation($id);
if (!$annotation) {
	forward();
}

$page = get_entity($annotation->entity_guid);
if (!$page) {
	
}

elgg_set_page_owner_guid($page->getContainerGUID());

group_gatekeeper();

$container = elgg_get_page_owner();
if (!$container) {
}

$title = $page->title . ": " . elgg_echo('pages:revision');

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, "pg/pages/group/$container->guid/owner");
} else {
	elgg_push_breadcrumb($container->name, "pg/pages/owner/$container->username");
}
pages_prepare_parent_breadcrumbs($page);
elgg_push_breadcrumb($page->title, $page->getURL());
elgg_push_breadcrumb(elgg_echo('pages:revision'));

$content = elgg_view('object/page_top', array(
	'entity' => $page,
	'revision' => $annotation,
	'full' => true,
));

$sidebar = elgg_view('pages/sidebar/history', array('page' => $page));

$body = elgg_view_layout('content', array(
	'filter' => '',
	'buttons' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
