<?php
/**
 * Action for deleting a wire post
 * 
 */

// Get input data
$guid = (int) get_input('guid');

// Make sure we actually have permission to edit
$thewire = get_entity($guid);
if ($thewire->getSubtype() == "thewire" && $thewire->canEdit()) {

	// unset reply metadata on children
	$children = elgg_get_entities_from_relationship(array(
		'relationship' => 'parent',
		'relationship_guid' => $post_guid,
		'inverse_relationship' => true,
	));
	if ($children) {
		foreach ($children as $child) {
			$child->reply = false;
		}
	}

	// Get owning user
	$owner = get_entity($thewire->getOwner());

	// Delete it
	$rowsaffected = $thewire->delete();
	if ($rowsaffected > 0) {
		// Success message
		system_message(elgg_echo("thewire:deleted"));
	} else {
		register_error(elgg_echo("thewire:notdeleted"));
	}

	forward("thewire/owner/" . $owner->username);
}
