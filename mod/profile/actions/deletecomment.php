<?php
/**
 * Elgg profile commentwall: delete message action
 */

// Ensure we're logged in
if (!isloggedin()) forward();
		
// Make sure we can get the comment in question
$annotation_id = (int) get_input('annotation_id');
		
//make sure that there is a message on the commentwall matching the passed id
if ($message = get_annotation($annotation_id)) {
	//grab the user or group entity
	$entity = get_entity($message->entity_guid);
   //check to make sure the current user can actually edit the commentwall
	if ($message->canEdit()) {
   			//delete the comment
			$message->delete();
			//display message
			system_message(elgg_echo("profile:commentwall:deleted"));
			forward($_SERVER['HTTP_REFERER']);
	}
		
} else {
	system_message(elgg_echo("profile:commentwall:notdeleted"));
}
		
forward($_SERVER['HTTP_REFERER']);