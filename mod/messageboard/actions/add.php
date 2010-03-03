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

	// Make sure we're logged in; forward to the front page if not
		if (!isloggedin()) forward();
		
	// Get input
		$message_content = get_input('message_content'); // the actual message
		$page_owner = get_input("pageOwner"); // the message board owner
		$message_owner = get_input("guid"); // the user posting the message
		$user = get_entity($page_owner); // the message board owner's details
		
	// Let's see if we can get a user entity from the specified page_owner
		if ($user && !empty($message_content)) {
    		
	        // If posting the comment was successful, say so
				if ($user->annotate('messageboard',$message_content,$user->access_id, $_SESSION['user']->getGUID())) {
					
					global $CONFIG;
					
					if ($user->getGUID() != $_SESSION['user']->getGUID())
					notify_user($user->getGUID(), $_SESSION['user']->getGUID(), elgg_echo('messageboard:email:subject'), 
						sprintf(
									elgg_echo('messageboard:email:body'),
									$_SESSION['user']->name,
									$message_content,
									$CONFIG->wwwroot . "pg/messageboard/" . $user->username,
									$_SESSION['user']->name,
									$_SESSION['user']->getURL()
								)
					); 
					
    				system_message(elgg_echo("messageboard:posted"));
    				// add to river
				    add_to_river('river/object/messageboard/create','messageboard',$_SESSION['user']->guid,$user->guid);

					
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

?>