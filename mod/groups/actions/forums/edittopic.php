<?php

    /**
	 * Elgg groups plugin edit topic action.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

    // Make sure we're logged in (send us to the front page if not)
		if (!isloggedin()) forward();
		
	// Check the user is a group member
	    $group_entity =  get_entity(get_input('group_guid'));
	    if (!$group_entity->isMember($vars['user'])) forward();
     

	// Get input data
	    $title = strip_tags(get_input('topictitle'));
		$message = get_input('topicmessage');
		$message_id = get_input('message_id');
		$tags = get_input('topictags');
		$topic_guid = get_input('topic');
		$access = get_input('access_id');
		$group_guid = get_input('group_guid');
		//$user = $_SESSION['user']->getGUID(); // you need to be logged in to comment on a group forum
		$status = get_input('status'); // sticky, resolved, closed
		
	// Convert string of tags into a preformatted array
		 $tagarray = string_to_tag_array($tags);
		
	// Make sure we actually have permission to edit
		$topic = get_entity($topic_guid);
		if ($topic)
		{
		
			$user = $topic->getOwner(); 
			
			if ($topic->getSubtype() == "groupforumtopic") {
		
			// Convert string of tags into a preformatted array
				$tagarray = string_to_tag_array($tags);
				
			// Make sure the title isn't blank
				if (empty($title) || empty($message)) {
					register_error(elgg_echo("groupstopic:blank"));
			
			// Otherwise, save the forum
				} else {
					
			        $topic->access_id = $access;
					
			// Set its title
					$topic->title = $title;
					
			// if no tags are present, clear existing ones
					if (is_array($tagarray)) {
						$topic->tags = $tagarray;
					} else $topic->clearMetadata('tags');
					
			// edit metadata
			       $topic->status = $status; // the current status i.e sticky, closed, resolved
			       
	        // now let's edit the message annotation
				   update_annotation($message_id, "group_topic_post", $message, "",$user, $access);
			       
			// save the changes
	            if (!$topic->save()) {
				//		register_error(elgg_echo("forumtopic:error"));
				}
				
	           // Success message
					system_message(elgg_echo("groups:forumtopic:edited"));
					
			    }
	        }
		}
		// Forward to the group forum page
	        global $CONFIG;
	        $url = $CONFIG->wwwroot . "pg/groups/forum/{$group_guid}/";
			forward($url);
		
?>

