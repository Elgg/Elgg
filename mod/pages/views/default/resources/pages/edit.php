<?php
/**
 * Edit a page
 */

$page_guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($page_guid, 'object', 'page');

$page = get_entity($page_guid);
if (!$page->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

$container = $page->getContainerEntity();
if ($container) {
	elgg_push_collection_breadcrumbs('object', 'page', $container);
}

pages_prepare_parent_breadcrumbs($page);
elgg_push_breadcrumb($page->getDisplayName(), $page->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo('edit:object:page');

$vars = pages_prepare_form_vars($page, $page->getParentGUID());
$content = elgg_view_form('pages/edit', [], $vars);

$body = elgg_view_layout('content', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar/navigation', [
		'page' => $page,
	]),
]);

echo elgg_view_page($title, $body);
