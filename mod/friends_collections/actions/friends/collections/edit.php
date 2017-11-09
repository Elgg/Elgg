<?php
/**
 * Friends collection edit action
 */

elgg_make_sticky_form('friends/collections/edit');

$collection_name = elgg_get_title_input('collection_name');
$friend_guids = (array) get_input('collection_friends', []);
$collection_id = get_input('collection_id');

if (!$collection_name) {
	return elgg_error_response(elgg_echo('friends:collections:edit:no_name'));
}

if (!$collection_id) {
	$collection_id = create_access_collection($collection_name, elgg_get_logged_in_user_guid(), 'friends_collection');
}

$collection = get_access_collection($collection_id);

if (!$collection instanceof ElggAccessCollection || !$collection->canEdit()) {
	return elgg_error_response(elgg_echo('friends:collections:edit:permissions'));
}

if ($collection->name != $collection_name) {
	$collection->name = $collection_name;
	$collection->save();
}

$count = 0;
foreach ($friend_guids as $friend_guid) {
	if (!$collection->hasMember($friend_guid) && $collection->addMember($friend_guid)) {
		$count++;
	}
}

if ($count > 0) {
	elgg_clear_sticky_form('friends/collections/edit');

	$data = [
		'collection' => $collection,
		'count' => $count,
	];
	$msg = elgg_echo('friends:collections:edit:success', [$count]);
	return elgg_ok_response($data, $msg, $collection->getURL());
} else {
	return elgg_error_response(elgg_echo('friends:collections:edit:fail'));
}
