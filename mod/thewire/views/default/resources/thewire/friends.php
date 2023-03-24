<?php
/**
 * Wire posts of your friends
 */

$owner = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'thewire', $owner, true);

$content = '';
if (elgg_get_logged_in_user_guid() === $owner->guid) {
	$content .= elgg_view_form('thewire/add', [
		'class' => 'thewire-form',
	]);
}

$content .= elgg_view('thewire/listing/friends', [
	'entity' => $owner,
]);

echo elgg_view_page(elgg_echo('collection:object:thewire:friends'), [
	'content' => $content,
	'filter_value' => 'friends',
]);
