<?php
/**
 * Remove member from access collection
 */

$collection_id = (int) get_input('collection_id');
$collection = get_access_collection($collection_id);
$user_guid = get_input('user_guid');

if (!$collection instanceof ElggAccessCollection || !$collection->canEdit()) {
	return elgg_error_response(elgg_echo('friends:collections:remove_member:permissions'));
}

if ($collection->removeMember($user_guid)) {
	$data = [
		'collection' => $collection,
	];
	return elgg_ok_response($data, elgg_echo('friends:collections:remove_member:success'));
} else {
	return elgg_error_response(elgg_echo('friends:collections:remove_member:fail'));
}
