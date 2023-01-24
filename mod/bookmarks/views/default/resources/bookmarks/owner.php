<?php
/**
 * Elgg bookmarks plugin everyone page
 */

$user = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'bookmarks', $user);

elgg_register_title_button('add', 'object', 'bookmarks');

$vars['entity'] = $user;

echo elgg_view_page(elgg_echo('collection:object:bookmarks'), [
	'filter_value' => $user->guid === elgg_get_logged_in_user_guid() ? 'mine' : 'none',
	'content' => elgg_view('bookmarks/listing/owner', $vars),
	'sidebar' => elgg_view('bookmarks/sidebar', $vars),
]);
