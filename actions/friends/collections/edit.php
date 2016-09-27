<?php
/**
 * Friends collection edit action
 *
 * @package Elgg.Core
 * @subpackage Friends.Collections
 */

$collection_id = get_input('collection_id');
$friends = get_input('friend');

// check it exists and we can edit
if (!can_edit_access_collection($collection_id)) {
	register_error(elgg_echo('friends:collection:edit_failed'));
	forward(REFERRER);
}

if (update_access_collection($collection_id, $friends)) {
	system_message(elgg_echo('friends:collections:edited'));
	if (elgg_is_xhr()) {
		echo json_encode(array(
			'membership' => elgg_view('core/friends/collection/membership', array(
				'collection' => get_access_collection($collection_id),
			)),
			'count' => count($friends),
		));
	}
} else {
	register_error(elgg_echo('friends:collection:edit_failed'));
}

forward(REFERER);