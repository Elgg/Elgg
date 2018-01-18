<?php
/**
 * Add bookmark page
 *
 * @package Bookmarks
 */
elgg_gatekeeper();

$title = elgg_echo('add:object:bookmarks');

$page_owner = elgg_get_page_owner_entity();

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
