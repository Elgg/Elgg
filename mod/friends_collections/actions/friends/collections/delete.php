<?php
/**
 * Delete access collection
 */

$collection_id = (int) get_input('collection_id');
$collection = get_access_collection($collection_id);

if (!$collection instanceof ElggAccessCollection || !$collection->canEdit()) {
	return elgg_error_response(elgg_echo('friends:collections:delete:permissions'));
}

$data = [
	'collection' => $collection->toObject(),
];

if ($collection->delete()) {
	return elgg_ok_response($data, elgg_echo('friends:collections:delete:success'));
} else {
	return elgg_error_response(elgg_echo('friends:collections:delete:fail'));
}
