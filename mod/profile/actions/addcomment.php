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
	if ($user->annotate('commentwall',$message_content,$user->access_id, $_SESSION['user']->getGUID())) {
				
			global $CONFIG;
				
			if ($user->getGUID() != $_SESSION['user']->getGUID())
			notify_user($user->getGUID(), $_SESSION['user']->getGUID(), elgg_echo('profile:comment:subject'), 
			sprintf(
							elgg_echo('profile:comment:body'),
							$_SESSION['user']->name,
							$message_content,
							$CONFIG->wwwroot . "pg/profile/" . $user->username,
							$_SESSION['user']->name,
							$_SESSION['user']->getURL()
						)
			); 
					
   			system_message(elgg_echo("profile:commentwall:posted"));
   			// add to river
		    add_to_river('river/object/profile/commentwall/create','commentwall',$_SESSION['user']->guid,$user->guid);
				
	} else {
		register_error(elgg_echo("profile:commentwall:failure"));
	}
		
} else {
	register_error(elgg_echo("profile:commentwall:blank"));
}
		
// Forward back to the messageboard
forward($_SERVER['HTTP_REFERER']);