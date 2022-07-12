<?php
/**
 * Delete access collection
 */

$collection_id = (int) get_input('collection_id');

$collection = elgg_get_access_collection($collection_id);
if (!$collection instanceof \ElggAccessCollection || !$collection->canEdit()) {
	return elgg_error_response(elgg_echo('friends:collections:delete:permissions'));
}

if (!$collection->delete()) {
	return elgg_error_response(elgg_echo('friends:collections:delete:fail'));
}

$data = [
	'collection' => $collection->toObject(),
];
return elgg_ok_response($data, elgg_echo('friends:collections:delete:success'));
