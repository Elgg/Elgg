<?php
/**
 * Add bookmark page
 *
 * @package ElggBookmarks
 */

$bookmark_guid = get_input('guid');
$bookmark = get_entity($bookmark_guid);

if (!elgg_instanceof($bookmark, 'object', 'bookmarks') || !$bookmark->canEdit()) {
	register_error(elgg_echo('bookmarks:unknown_bookmark'));
	forward(REFERRER);
}

$page_owner = elgg_get_page_owner_entity();

$title = elgg_echo('bookmarks:edit');
elgg_push_breadcrumb($title);

$vars = bookmarks_prepare_form_vars($bookmark);
$content = elgg_view_form('bookmarks/save', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'buttons' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);