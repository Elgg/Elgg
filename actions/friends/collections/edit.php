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
	system_message(elgg_echo('friends:collection:edit_failed'));
}

if (update_access_collection($collection_id, $friends)) {
	system_message(elgg_echo('friends:collections:edited'));
} else {
	system_message(elgg_echo('friends:collection:edit_failed'));
}

forward(REFERER);