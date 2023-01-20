<?php
/**
 * Show a listing of files owned by a user
 */

$user = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'file', $user);

elgg_register_title_button('add', 'object', 'file');

$title = elgg_echo('collection:object:file:owner', [$user->getDisplayName()]);

$listing_params = $vars;
$listing_params['entity'] = $user;

echo elgg_view_page($title, [
	'content' => elgg_view('file/listing/owner', $listing_params),
	'sidebar' => elgg_view('file/sidebar'),
	'filter_value' => $user->guid == elgg_get_logged_in_user_guid() ? 'mine' : 'none',
]);
