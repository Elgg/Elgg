<?php
/**
 * Elgg profile commentwall: add message action
 */

// Make sure we're logged in; forward to the front page if not
if (!isloggedin()) forward();

// Get input
$message_content = get_input('message_content'); // the actual message
$page_owner = get_input("pageOwner"); // the message board owner
$message_owner = get_input("guid"); // the user posting the message
$user = get_entity($page_owner); // the commentwall owner details

// Let's see if we can get a user entity from the specified page_owner
if ($user && !empty($message_content)) {

	// If posting the comment was successful, say so
	if ($user->annotate('commentwall',$message_content,$user->access_id, get_loggedin_userid())) {

			global $CONFIG;

			if ($user->getGUID() != get_loggedin_userid())
			notify_user($user->getGUID(), get_loggedin_userid(), elgg_echo('profile:comment:subject'),
			elgg_echo('profile:comment:body', array(
							get_loggedin_user()->name,
							$message_content,
							elgg_get_site_url() . "pg/profile/" . $user->username,
							get_loggedin_user()->name,
							get_loggedin_user()->getURL()
						))
			);

			system_message(elgg_echo("profile:commentwall:posted"));
			// add to river
			add_to_river('river/object/profile/commentwall/create','commentwall',get_loggedin_userid(),$user->guid);

	} else {
		register_error(elgg_echo("profile:commentwall:failure"));
	}

} else {
	register_error(elgg_echo("profile:commentwall:blank"));
}

// Forward back to the messageboard
forward(REFERER);