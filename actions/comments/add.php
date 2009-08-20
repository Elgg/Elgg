<?php

	/**
	 * Elgg add comment action
	 * 
	 * @package Elgg

	 * @author Curverider <curverider.co.uk>

	 * @link http://elgg.org/
	 */

	// Make sure we're logged in; forward to the front page if not
		gatekeeper();
		action_gatekeeper();
		
	// Get input
		$entity_guid = (int) get_input('entity_guid');
		$comment_text = get_input('generic_comment');
		
	// Let's see if we can get an entity with the specified GUID
		if ($entity = get_entity($entity_guid)) {
			
	        // If posting the comment was successful, say so
				if ($entity->annotate('generic_comment',$comment_text,$entity->access_id, $_SESSION['guid'])) {
					
					if ($entity->owner_guid != $_SESSION['user']->getGUID())
					notify_user($entity->owner_guid, $_SESSION['user']->getGUID(), elgg_echo('generic_comment:email:subject'), 
						sprintf(
									elgg_echo('generic_comment:email:body'),
									$entity->title,
									$_SESSION['user']->name,
									$comment_text,
									$entity->getURL(),
									$_SESSION['user']->name,
									$_SESSION['user']->getURL()
								)
					); 
					
					system_message(elgg_echo("generic_comment:posted"));
					//add to river
					add_to_river('annotation/annotate','comment',$_SESSION['user']->guid,$entity->guid);

					
				} else {
					register_error(elgg_echo("generic_comment:failure"));
				}
				
		} else {
		
			register_error(elgg_echo("generic_comment:notfound"));
			
		}
		
	// Forward to the 
		forward($entity->getURL());

?>