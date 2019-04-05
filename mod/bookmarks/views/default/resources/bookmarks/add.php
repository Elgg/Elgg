<?php
/**
 * Add bookmark page
 *
 * @package Bookmarks
 */

$title = elgg_echo('add:object:bookmarks');

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid);

$page_owner = get_entity($guid);

if (!$page_owner->canWriteToContainer(0, 'object', 'bookmarks')) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'bookmarks', $page_owner);
elgg_push_breadcrumb($title);

$vars = bookmarks_prepare_form_vars();
$content = elgg_view_form('bookmarks/save', [], $vars);

$body = elgg_view_layout('default', [
	'filter_id' => 'bookmarks/edit',
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
