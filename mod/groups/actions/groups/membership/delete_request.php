<?php
/**
 * Delete a request to join a closed group.
 *
 * @package ElggGroups
 */

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$group_guid = get_input('group_guid');

$user = get_entity($user_guid);
$group = get_entity($group_guid);

// If join request made
if (check_entity_relationship($user->guid, 'membership_request', $group->guid)) {
	remove_entity_relationship($user->guid, 'membership_request', $group->guid);
	system_message(elgg_echo("groups:joinrequestkilled"));
}

forward(REFERER);
