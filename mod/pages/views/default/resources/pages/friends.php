<?php
/**
 * List a user's friends' pages
 */

$owner = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'page', $owner, true);

elgg_register_title_button('add', 'object', 'page');

echo elgg_view_page(elgg_echo('collection:object:page:friends'), [
	'filter_value' => $owner->guid === elgg_get_logged_in_user_guid() ? 'friends' : 'none',
	'content' => elgg_view('pages/listing/friends', [
		'entity' => $owner,
	]),
]);
