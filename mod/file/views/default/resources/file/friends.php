<?php
/**
 * Friends Files
 *
 * @package ElggFile
 */

$username = elgg_extract('username', $vars);
$owner = get_user_by_username($username);

if (!$owner) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'file', $owner, true);

elgg_register_title_button('file', 'add', 'object', 'file');

$title = elgg_echo("collection:object:file:friends");

$params = $vars;
$params['entity'] = $owner;
$content = elgg_view('file/listing/friends', $params);

$body = elgg_view_layout('content', [
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
