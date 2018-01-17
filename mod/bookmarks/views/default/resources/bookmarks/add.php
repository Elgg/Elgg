<?php
/**
 * Add bookmark page
 *
 * @package Bookmarks
 */
elgg_gatekeeper();

$page_owner = elgg_get_page_owner_entity();

$title = elgg_echo('add:object:bookmarks');
elgg_push_breadcrumb($title);

$vars = bookmarks_prepare_form_vars();
$content = elgg_view_form('bookmarks/save', [], $vars);

$body = elgg_view_layout('content', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
