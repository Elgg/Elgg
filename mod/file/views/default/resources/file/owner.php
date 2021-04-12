<?php
/**
 * Individual's or group's files
 */

$username = elgg_extract('username', $vars);
if ($username) {
	$user = get_user_by_username($username);
	$guid = $user->guid;
} else {
	// Backward compatibility
	$guid = elgg_extract('guid', $vars);
}

elgg_entity_gatekeeper($guid);

elgg_group_tool_gatekeeper('file', $guid);

$owner = get_entity($guid);

elgg_push_collection_breadcrumbs('object', 'file', $owner);

elgg_register_title_button('file', 'add', 'object', 'file');

$title = elgg_echo('collection:object:file:owner', [$owner->getDisplayName()]);

$listing_params = $vars;
$listing_params['entity'] = $owner;

echo elgg_view_page($title, [
	'content' => elgg_view('file/listing/owner', $listing_params),
	'sidebar' => elgg_view('file/sidebar'),
	'filter_value' => $owner->guid == elgg_get_logged_in_user_guid() ? 'mine' : 'none',
]);
