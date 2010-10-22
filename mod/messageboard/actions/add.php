<?php

/**
 * Elgg Message board: add message action
 *
 * @package ElggMessageBoard
 */

// Get input
$message_content = get_input('message_content'); // the actual message
$page_owner = get_input("pageOwner"); // the message board owner
$user = get_entity($page_owner); // the message board owner's details

// Let's see if we can get a user entity from the specified page_owner
if ($user && !empty($message_content)) {

	if (messageboard_add(get_loggedin_user(), $user, $message_content, $user->access_id)) {
		system_message(elgg_echo("messageboard:posted"));
	} else {
		register_error(elgg_echo("messageboard:failure"));
	}

	//set the url to return the user to the correct message board
	$url = "pg/messageboard/" . $user->username;

} else {

	register_error(elgg_echo("messageboard:blank"));

	//set the url to return the user to the correct message board
	$url = "pg/messageboard/" . $user->username;

}

// Forward back to the messageboard
forward($url);
