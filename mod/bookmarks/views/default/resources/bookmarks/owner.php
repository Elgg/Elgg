<?php
/**
 * Elgg bookmarks plugin everyone page
 *
 * @package Bookmarks
 */
$username = elgg_extract('username', $vars);

$user = get_user_by_username($username);
if (!$user) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'bookmarks', $user);

elgg_register_title_button('bookmarks', 'add', 'object', 'bookmarks');

$vars['entity'] = $user;

$content = elgg_view('bookmarks/listing/owner', $vars);

$title = elgg_echo('collection:object:bookmarks');

$body = elgg_view_layout('default', [
	'filter_value' => $user->guid == elgg_get_logged_in_user_guid() ? 'mine' : 'none',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('bookmarks/sidebar', $vars),
]);

echo elgg_view_page($title, $body);
