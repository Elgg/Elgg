<?php
/**
 * Delete an invitation to join a group.
 *
 * @package ElggGroups
 */

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$group_guid = get_input('group_guid');

$user = get_entity($user_guid);
$group = get_entity($group_guid);

// If join request made
if (check_entity_relationship($group->guid, 'invited', $user->guid)) {
	remove_entity_relationship($group->guid, 'invited', $user->guid);
	system_message(elgg_echo("groups:invitekilled"));
}

forward(REFERER);
