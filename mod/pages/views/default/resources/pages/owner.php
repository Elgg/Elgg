<?php
/**
 * List a user's or group's pages
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$username = elgg_extract('username', $vars);
$owner = get_user_by_username($username);

if (!$owner instanceof ElggUser) {
	throw new EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'page', $owner);

elgg_register_title_button('pages', 'add', 'object', 'page');

echo elgg_view_page(elgg_echo('collection:object:page'), [
	'filter_value' => $owner->guid == elgg_get_logged_in_user_guid() ? 'mine' : 'none',
	'content' => elgg_view('pages/listing/owner', [
		'entity' => $owner,
	]),
	'sidebar' => elgg_view('pages/sidebar', $vars),
]);
