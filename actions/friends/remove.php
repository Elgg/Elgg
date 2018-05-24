<?php
/**
 * Elgg remove friend action
 *
 * @package Elgg.Core
 * @subpackage Friends.Management
 */

// Get the GUID of the user to friend
$friend_guid = (int) get_input('friend');

$friend = get_user($friend_guid);
if (!$friend) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$user = elgg_get_logged_in_user_entity();
if (!$user->isFriendsWith($friend->guid)) {
	return elgg_ok_response('', elgg_echo('friends:remove:no_friend', [$friend->getDisplayName()]));
}

if (!elgg_get_logged_in_user_entity()->removeFriend($friend->guid)) {
	return elgg_error_response(elgg_echo('friends:remove:failure', [$friend->getDisplayName()]));
}

return elgg_ok_response('', elgg_echo('friends:remove:successful', [$friend->getDisplayName()]));
