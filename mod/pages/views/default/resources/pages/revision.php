<?php
/**
 * View a revision of page
 */

$id = elgg_extract('id', $vars);
$annotation = elgg_get_annotation_from_id($id);
if (!$annotation instanceof ElggAnnotation) {
	throw new \Elgg\EntityNotFoundException();
}

$page = get_entity($annotation->entity_guid);
if (!$page instanceof ElggPage) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_set_page_owner_guid($page->getContainerGUID());

elgg_group_gatekeeper();

$container = elgg_get_page_owner_entity();
if (!$container) {
	throw new \Elgg\EntityNotFoundException();
}

$title = "{$page->getDisplayName()}: " . elgg_echo('pages:revision');

elgg_push_breadcrumb(elgg_echo('pages'), 'pages/all');

if ($container instanceof ElggUser) {
	elgg_push_breadcrumb($container->getDisplayName(), "pages/owner/{$container->username}");
} else if ($container instanceof ElggGroup) {
	elgg_push_breadcrumb($container->getDisplayName(), "pages/group/{$container->guid}");
}

pages_prepare_parent_breadcrumbs($page);
elgg_push_breadcrumb($page->getDisplayName(), $page->getURL());
elgg_push_breadcrumb(elgg_echo('pages:revision'));

$content = elgg_view_entity($page, [
	'revision' => $annotation,
]);

$sidebar = elgg_view('pages/sidebar/history', [
	'page' => $page,
]);

$body = elgg_view_layout('content', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
]);

echo elgg_view_page($title, $body);
