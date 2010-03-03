<?php

	/**
	 * Elgg Message board: delete message action
	 * 
	 * @package ElggMessageBoard
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

	// Ensure we're logged in
		if (!isloggedin()) forward();
		
	// Make sure we can get the comment in question
		$annotation_id = (int) get_input('annotation_id');
		
		//make sure that there is a message on the message board matching the passed id
		if ($message = get_annotation($annotation_id)) {
    		
    		//grab the user or group entity
    		$entity = get_entity($message->entity_guid);
    		
            //check to make sure the current user can actually edit the message board
			if ($message->canEdit()) {
    			//delete the comment
				$message->delete();
				//display message
				system_message(elgg_echo("messageboard:deleted"));
				//generate the url to forward to 
				$url = "pg/messageboard/" . $entity->username;
				//forward the user back to their message board
				forward($url);
			}
			
		} else {
			$url = "";
			system_message(elgg_echo("messageboard:notdeleted"));
		}
		
		forward($url);

?>