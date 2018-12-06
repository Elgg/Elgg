<?php
/**
 * Action for deleting a wire post
 */

// Get input data
$guid = (int) get_input('guid');

elgg_entity_gatekeeper($guid, 'object', 'thewire');

// Make sure we actually have permission to edit
$thewire = get_entity($guid);
if (!$thewire->canDelete()) {
	return elgg_error_response();
}

// unset reply metadata on children
$children = elgg_get_entities([
	'relationship' => 'parent',
	'relationship_guid' => $guid,
	'inverse_relationship' => true,
	'limit' => false,
]);
if ($children) {
	foreach ($children as $child) {
		unset($child->reply);
	}
}

// Get owning user
$owner = $thewire->getOwnerEntity();

// Delete it
if (!$thewire->delete()) {
	return elgg_error_response(elgg_echo('thewire:notdeleted'));
}

return elgg_ok_response('', elgg_echo('thewire:deleted'), elgg_generate_url('collection:object:thewire:owner', [
	'username' => $owner->username,
]));
