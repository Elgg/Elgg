<?php
/**
 * Elgg bookmarks plugin friends page
 *
 * @package ElggBookmarks
 */

$username = elgg_extract('username', $vars);

$user = get_user_by_username($username);
if (!$user) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'bookmarks', $user, true);

elgg_register_title_button('bookmarks', 'add', 'object', 'bookmarks');

$title = elgg_echo('collection:object:bookmarks:friends');

$content = elgg_view('bookmarks/listing/friends', [
	'entity' => $user,
]);

$body = elgg_view_layout('default', [
	'filter_value' => $user->guid == elgg_get_logged_in_user_guid() ? 'friends' : 'none',
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
