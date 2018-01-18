<?php
/**
 * List a user's friends' pages
 */

$username = elgg_extract('username', $vars);
$owner = get_user_by_username($username);

if (!$owner instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'page', $owner, true);

elgg_register_title_button('pages', 'add', 'object', 'page');

$title = elgg_echo('collection:object:page:friends');

$content = elgg_view('pages/listing/friends', [
	'entity' => $owner,
]);

$body = elgg_view_layout('content', [
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
