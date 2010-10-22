<?php

/**
 * Elgg friends: delete collection action
 *
 * @package Elgg
 * @subpackage Core


 */

// Make sure we're logged in (send us to the front page if not)
gatekeeper();

// Get input data
$collection_id = (int) get_input('collection');

// Check to see that the access collection exist and grab its owner
$get_collection = get_access_collection($collection_id);

if ($get_collection) {

	if ($get_collection->owner_guid == get_loggedin_userid()) {

		$delete_collection = delete_access_collection($collection_id);

		// Success message
		if ($delete_collection) {
			system_message(elgg_echo("friends:collectiondeleted"));
		} else {
			register_error(elgg_echo("friends:collectiondeletefailed"));
		}
	} else {
		// Failure message
		register_error(elgg_echo("friends:collectiondeletefailed"));
	}
} else {
	// Failure message
	register_error(elgg_echo("friends:collectiondeletefailed"));
}

// Forward to the collections page
forward("pg/collections/" . get_loggedin_user()->username);
