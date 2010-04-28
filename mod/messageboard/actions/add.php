<?php

/**
 * Elgg Message board: add message action
 *
 * @package ElggMessageBoard
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

// Get input
$message_content = get_input('message_content'); // the actual message
$page_owner = get_input("pageOwner"); // the message board owner
$message_owner = get_input("guid"); // the user posting the message
$user = get_entity($page_owner); // the message board owner's details

// Let's see if we can get a user entity from the specified page_owner
if ($user && !empty($message_content)) {

	// If posting the comment was successful, say so
	if ($user->annotate('messageboard',$message_content,$user->access_id, get_loggedin_userid())) {

		if ($user->getGUID() != get_loggedin_userid())
			notify_user($user->getGUID(), get_loggedin_userid(), elgg_echo('messageboard:email:subject'),
					sprintf(
					elgg_echo('messageboard:email:body'),
					get_loggedin_user()->name,
					$message_content,
					$CONFIG->wwwroot . "pg/messageboard/" . $user->username,
					get_loggedin_user()->name,
					get_loggedin_user()->getURL()
					)
			);

		system_message(elgg_echo("messageboard:posted"));
		// add to river
		add_to_river('river/object/messageboard/create','messageboard',get_loggedin_userid(),$user->guid);


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
