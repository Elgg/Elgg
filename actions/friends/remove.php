<?php
/**
 * Elgg remove friend action
 *
 * @package Elgg.Core
 * @subpackage Friends.Management
 */

// Get the GUID of the user to friend
$friend_guid = get_input('friend');
$friend = get_entity($friend_guid);
$errors = false;

// Get the user
try{
	if ($friend instanceof ElggUser) {
		elgg_get_logged_in_user_entity()->removeFriend($friend_guid);
	} else {
		register_error(elgg_echo("friends:remove:failure", array($friend->name)));
		$errors = true;
	}
} catch (Exception $e) {
	register_error(elgg_echo("friends:remove:failure", array($friend->name)));
	$errors = true;
}

if (!$errors) {
	system_message(elgg_echo("friends:remove:successful", array($friend->name)));
}

// Forward back to the page you made the friend on
forward(REFERER);
