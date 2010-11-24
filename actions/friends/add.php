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
	if (!get_loggedin_user()->addFriend($friend_guid)) {
		$errors = true;
	}
} catch (Exception $e) {
	register_error(elgg_echo("friends:add:failure", array($friend->name)));
	$errors = true;
}
if (!$errors) {
	// add to river
	add_to_river('friends/river/create', 'friend', get_loggedin_userid(), $friend_guid);
	system_message(elgg_echo("friends:add:successful", array($friend->name)));
}

// Forward back to the page you friended the user on
forward(REFERER);
