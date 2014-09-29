<?php
/**
 * Elgg remove friend action
 *
 * @package Elgg.Core
 * @subpackage Friends.Management
 */

// Get the GUID of the user to friend
$friend_guid = get_input('friend');
$friend = get_user($friend_guid);

if (!$friend) {
	register_error(elgg_echo('error:missing_data'));
	forward(REFERER);
}

if (!elgg_get_logged_in_user_entity()->removeFriend($friend->guid)) {
	register_error(elgg_echo("friends:remove:failure", array($friend->name)));
	forward(REFERER);
}

system_message(elgg_echo("friends:remove:successful", array($friend->name)));
forward(REFERER);

