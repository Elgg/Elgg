<?php
/**
 * User's wire posts
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$owner = elgg_get_page_owner_entity();
if (!$owner instanceof ElggUser) {
	throw new EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'thewire', $owner);

$content = '';

if (elgg_get_logged_in_user_guid() == $owner->guid) {
	$content .= elgg_view_form('thewire/add', [
		'class' => 'thewire-form',
	]);
}

$content .= elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'owner_guid' => $owner->guid,
	'limit' => get_input('limit', 15),
]);

echo elgg_view_page(elgg_echo('collection:object:thewire:owner', [$owner->getDisplayName()]), [
	'filter_value' => elgg_get_logged_in_user_guid() == $owner->guid ? 'mine' : 'none',
	'content' => $content,
	'sidebar' => elgg_view('thewire/sidebar'),
]);
