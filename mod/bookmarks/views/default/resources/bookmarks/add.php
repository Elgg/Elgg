<?php
/**
 * Add bookmark page
 *
 * @package Bookmarks
 */
elgg_gatekeeper();

elgg_load_library('elgg:bookmarks');
elgg_push_breadcrumb(elgg_echo('bookmarks'), 'bookmarks/all');

$page_owner = elgg_get_page_owner_entity();

$title = elgg_echo('bookmarks:add');
elgg_push_breadcrumb($title);

$vars = bookmarks_prepare_form_vars();
$content = elgg_view_form('bookmarks/save', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);