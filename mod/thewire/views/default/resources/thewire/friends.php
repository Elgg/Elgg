<?php
/**
 * Wire posts of your friends
 */

$owner = elgg_get_page_owner_entity();
if (!$owner instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException();
}

$title = elgg_echo('collection:object:thewire:friends');

elgg_push_collection_breadcrumbs('object', 'thewire', $owner, true);

$content = '';
if (elgg_get_logged_in_user_guid() == $owner->guid) {
	$content .= elgg_view_form('thewire/add', [
		'class' => 'thewire-form',
		'prevent_double_submit' => true,
	]);
	$content .= elgg_view('input/urlshortener');
}

$content .= elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'relationship_join_on' => 'container_guid',
]);

$body = elgg_view_layout('content', [
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
