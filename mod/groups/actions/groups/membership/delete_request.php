<?php
/**
 * Delete a request to join a closed group.
 *
 * @package ElggGroups
 */

$user_guid = (int) get_input('user_guid', elgg_get_logged_in_user_guid());
$group_guid = (int) get_input('group_guid');

$user = get_user($user_guid);
$group = get_entity($group_guid);

if (!$user && !($group instanceof \ElggGroup)) {
	return elgg_error_response();
}

// If join request made
$message = '';
if (remove_entity_relationship($user->guid, 'membership_request', $group->guid)) {
	$message = elgg_echo('groups:joinrequestkilled');
}

return elgg_ok_response('', $message);
