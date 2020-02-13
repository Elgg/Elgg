<?php
/**
 * Elgg bookmarks plugin everyone page
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$username = elgg_extract('username', $vars);

$user = get_user_by_username($username);
if (!$user) {
	throw new EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'bookmarks', $user);

elgg_register_title_button('bookmarks', 'add', 'object', 'bookmarks');

$vars['entity'] = $user;

echo elgg_view_page(elgg_echo('collection:object:bookmarks'), [
	'filter_value' => $user->guid == elgg_get_logged_in_user_guid() ? 'mine' : 'none',
	'content' => elgg_view('bookmarks/listing/owner', $vars),
	'sidebar' => elgg_view('bookmarks/sidebar', $vars),
]);
