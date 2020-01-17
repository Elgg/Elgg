<?php
/**
 * Delete an invitation to join a group.
 */

$user_guid = (int) get_input('user_guid', elgg_get_logged_in_user_guid());
$group_guid = (int) get_input('group_guid');

$user = get_user($user_guid);

// invisible groups require overriding access to delete invite
$group = elgg_call(ELGG_IGNORE_ACCESS, function() use ($group_guid) {
	return get_entity($group_guid);
});

if (!$user && !($group instanceof \ElggGroup)) {
	return elgg_error_response();
}

// If join request made
$message = '';
if (remove_entity_relationship($group->guid, 'invited', $user->guid)) {
	$message = elgg_echo('groups:invitekilled');
}

return elgg_ok_response('', $message);
