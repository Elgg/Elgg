<?php
/**
 * Elgg friends: delete collection action
 *
 * @package Elgg.Core
 * @subpackage Friends.Collections
 */

$collection_id = (int) get_input('collection');

// check the ACL exists and we can edit
if (!can_edit_access_collection($collection_id)) {
	register_error(elgg_echo("friends:collectiondeletefailed"));
	forward(REFERER);
}

if (delete_access_collection($collection_id)) {
	system_message(elgg_echo("friends:collectiondeleted"));
} else {
	register_error(elgg_echo("friends:collectiondeletefailed"));
}

forward(REFERER);
