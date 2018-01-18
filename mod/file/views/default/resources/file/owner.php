<?php
/**
 * Individual's or group's files
 *
 * @package ElggFile
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

$owner = get_entity($guid);

elgg_push_collection_breadcrumbs('object', 'file', $owner);

elgg_register_title_button('file', 'add', 'object', 'file');

$params = [];

if ($owner->guid == elgg_get_logged_in_user_guid()) {
	// user looking at own files
	$params['filter_context'] = 'mine';
} else if ($owner instanceof ElggUser) {
	// someone else's files
	// do not show select a tab when viewing someone else's posts
	$params['filter_context'] = 'none';
}

$title = elgg_echo("collection:object:file:owner", [$owner->getDisplayName()]);

$listing_params = $vars;
$listing_params['entity'] = $owner;
$content = elgg_view('file/listing/owner', $listing_params);

$sidebar = elgg_view('file/sidebar');

$params['content'] = $content;
$params['title'] = $title;
$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
