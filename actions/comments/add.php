<?php

	/**
	 * Elgg add comment action
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Make sure we're logged in; forward to the front page if not
		if (!isloggedin()) forward();
		
	// Get input
		$entity_guid = (int) get_input('entity_guid');
		$comment_text = get_input('generic_comment');
		
	// Let's see if we can get an entity with the specified GUID
		if ($entity = get_entity($entity_guid)) {
			
	        // If posting the comment was successful, say so
				if ($entity->annotate('generic_comment',$comment_text,$entity->access_id, $_SESSION['guid'])) {
					
					system_message(elgg_echo("generic_comment:posted"));
					
				} else {
					system_message(elgg_echo("generic_comment:failure"));
				}
				
		} else {
		
			system_message(elgg_echo("generic_comment:notfound"));
			
		}
		
	// Forward to the 
		forward($entity->getURL());

?>