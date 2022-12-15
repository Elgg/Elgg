<?php
/**
 * User's wire posts
 */

$owner = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'thewire', $owner);

$content = '';
if (elgg_get_logged_in_user_guid() === $owner->guid) {
	$content .= elgg_view_form('thewire/add', [
		'class' => 'thewire-form',
	]);
}

$content .= elgg_view('thewire/listing/owner', [
	'entity' => $owner,
]);

echo elgg_view_page(elgg_echo('collection:object:thewire:owner', [$owner->getDisplayName()]), [
	'content' => $content,
	'sidebar' => elgg_view('thewire/sidebar'),
	'filter_value' => elgg_get_logged_in_user_guid() === $owner->guid ? 'mine' : 'none',
]);
