<?php
/**
 * Wire posts of your friends
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$owner = elgg_get_page_owner_entity();
if (!$owner instanceof ElggUser) {
	throw new EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'thewire', $owner, true);

$content = '';
if (elgg_get_logged_in_user_guid() == $owner->guid) {
	$content .= elgg_view_form('thewire/add', [
		'class' => 'thewire-form',
	]);
}

$content .= elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'relationship_join_on' => 'container_guid',
]);

echo elgg_view_page(elgg_echo('collection:object:thewire:friends'), [
	'filter_value' => 'friends',
	'content' => $content,
]);
