<?php
/**
 * Remove member from access collection
 */

$collection_id = (int) get_input('collection_id');
$user_guid = (int) get_input('user_guid');

$collection = elgg_get_access_collection($collection_id);
if (!$collection instanceof \ElggAccessCollection || !$collection->canEdit()) {
	return elgg_error_response(elgg_echo('friends:collections:remove_member:permissions'));
}

if (!$collection->removeMember($user_guid)) {
	return elgg_error_response(elgg_echo('friends:collections:remove_member:fail'));
}

$data = [
	'collection' => $collection,
];
return elgg_ok_response($data, elgg_echo('friends:collections:remove_member:success'));
