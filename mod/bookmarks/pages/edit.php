<?php
/**
 * Add bookmark page
 *
 * @package ElggBookmarks
 */

gatekeeper();
$bookmark_guid = get_input('guid');
$bookmark = get_entity($bookmark_guid);
$container_guid = (int) get_input('container_guid');
$container = get_entity($container_guid);

if (!elgg_instanceof($bookmark, 'object', 'bookmarks')) {
	register_error(elgg_echo('bookmarks:unknown_bookmark'));
	forward(REFERRER);
}

// for groups.
$container = $bookmark->getContainerEntity();
elgg_set_page_owner_guid($container->getGUID());

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