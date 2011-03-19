<?php
/**
 * Elgg add friend action
 *
 * @package Elgg.Core
 * @subpackage Friends.Management
 */

// Get the GUID of the user to friend
$friend_guid = get_input('friend');
$friend = get_entity($friend_guid);

$errors = false;

// Get the user
try {
	if (!elgg_get_logged_in_user_entity()->addFriend($friend_guid)) {
		$errors = true;
	}
} catch (Exception $e) {
	register_error(elgg_echo("friends:add:failure", array($friend->name)));
	$errors = true;
}
if (!$errors) {
	// add to river
	add_to_river('river/relationship/friend/create', 'friend', elgg_get_logged_in_user_guid(), $friend_guid);
	system_message(elgg_echo("friends:add:successful", array($friend->name)));
}

// Forward back to the page you friended the user on
forward(REFERER);
