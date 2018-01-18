<?php
/**
 * List a user's or group's pages
 */

$username = elgg_extract('username', $vars);
$owner = get_user_by_username($username);

if (!$owner instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException();
}

$title = elgg_echo('collection:object:page');

elgg_push_collection_breadcrumbs('object', 'page', $owner);

elgg_register_title_button('pages', 'add', 'object', 'page');

$content = elgg_view('pages/listing/owner', [
	'entity' => $owner,
]);

$sidebar = elgg_view('pages/sidebar/navigation', $vars);
$sidebar .= elgg_view('pages/sidebar');

$body = elgg_view_layout('default', [
	'filter_value' => $owner->guid == elgg_get_logged_in_user_guid() ? 'mine' : 'none',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
]);

echo elgg_view_page($title, $body);
