<?php
/**
 * Elgg remove friend action
 *
 * @package Elgg
 * @subpackage Core
 */

// Ensure we are logged in
gatekeeper();

// Get the GUID of the user to friend
$friend_guid = get_input('friend');
$friend = get_entity($friend_guid);
$errors = false;

// Get the user
try{
	if ($friend instanceof ElggUser) {
		get_loggedin_user()->removeFriend($friend_guid);
	} else{
		register_error(sprintf(elgg_echo("friends:remove:failure"), $friend->name));
		$errors = true;
	}
} catch (Exception $e) {
	register_error(sprintf(elgg_echo("friends:remove:failure"), $friend->name));
	$errors = true;
}

if (!$errors) {
	system_message(sprintf(elgg_echo("friends:remove:successful"), $friend->name));
}

// Forward back to the page you made the friend on
forward($_SERVER['HTTP_REFERER']);
