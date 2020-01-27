<?php
/**
 * User's wire posts
 */

$owner = elgg_get_page_owner_entity();
if (!$owner instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException();
}

$title = elgg_echo('collection:object:thewire:owner', [$owner->getDisplayName()]);

elgg_push_collection_breadcrumbs('object', 'thewire', $owner);

$context = '';
$content = '';

if (elgg_get_logged_in_user_guid() == $owner->guid) {
	$content .= elgg_view_form('thewire/add', [
		'class' => 'thewire-form',
	]);
	$content .= elgg_view('input/urlshortener');
	$context = 'mine';
}

$content .= elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'owner_guid' => $owner->guid,
	'limit' => get_input('limit', 15),
]);

$body = elgg_view_layout('content', [
	'filter_context' => $context,
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('thewire/sidebar'),
]);

echo elgg_view_page($title, $body);
