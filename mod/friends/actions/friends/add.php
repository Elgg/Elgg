<?php
/**
 * Elgg add friend action
 */

// Get the GUID of the user to friend
$friend_guid = (int) get_input('friend');
$friend = get_user($friend_guid);

if (!$friend instanceof \ElggUser) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$user = elgg_get_logged_in_user_entity();
if ($user->isFriendsWith($friend->guid)) {
	return elgg_ok_response('', elgg_echo('friends:add:duplicate', [$friend->getDisplayName()]));
}

if (!$user->addFriend($friend->guid, true)) {
	return elgg_error_response(elgg_echo('friends:add:failure', [$friend->getDisplayName()]));
}

return elgg_ok_response('', elgg_echo('friends:add:successful', [$friend->getDisplayName()]));
