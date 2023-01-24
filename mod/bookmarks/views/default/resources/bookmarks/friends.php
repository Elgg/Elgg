<?php
/**
 * Elgg bookmarks plugin friends page
 */

$user = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'bookmarks', $user, true);

elgg_register_title_button('add', 'object', 'bookmarks');

echo elgg_view_page(elgg_echo('collection:object:bookmarks:friends'), [
	'filter_value' => $user->guid == elgg_get_logged_in_user_guid() ? 'friends' : 'none',
	'content' => elgg_view('bookmarks/listing/friends', [
		'entity' => $user,
	]),
]);
