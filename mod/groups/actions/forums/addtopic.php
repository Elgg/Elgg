<?php

    /**
	 * Elgg groups plugin add topic action.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Make sure we're logged in; forward to the front page if not
		if (!isloggedin()) forward();
		
	// Check the user is a group member
	    $group_entity =  get_entity(get_input('group_guid'));
	    if (!$group_entity->isMember($vars['user'])) forward();
	    
	// Get input data
	    $title = strip_tags(get_input('topictitle'));
		$message = get_input('topicmessage');
		$tags = get_input('topictags');
		$access = get_input('access_id');
		$group_guid = (int) get_input('group_guid');
		$user = $_SESSION['user']->getGUID(); // you need to be logged in to comment on a group forum
		$status = get_input('status'); // sticky, resolved, closed
		
	// Convert string of tags into a preformatted array
		 $tagarray = string_to_tag_array($tags);
		
	// Make sure the title / message aren't blank
		if (empty($title) || empty($message)) {
			register_error(elgg_echo("grouptopic:blank"));
			forward("pg/groups/forum/{$group_guid}/");
			
	// Otherwise, save the topic
		} else {
			
	// Initialise a new ElggObject
			$grouptopic = new ElggObject();
	// Tell the system it's a group forum topic
			$grouptopic->subtype = "groupforumtopic";
	// Set its owner to the current user
			$grouptopic->owner_guid = $user;
	// Set the group it belongs to
			$grouptopic->container_guid = $group_guid;
	// For now, set its access to public (we'll add an access dropdown shortly)
			$grouptopic->access_id = $access;
	// Set its title and description appropriately
			$grouptopic->title = $title;
	// Before we can set metadata, we need to save the topic
			if (!$grouptopic->save()) {
				register_error(elgg_echo("grouptopic:error"));
				forward("pg/groups/forum/{$group_guid}/");
			}
	// Now let's add tags. We can pass an array directly to the object property! Easy.
			if (is_array($tagarray)) {
				$grouptopic->tags = $tagarray;
			}
	// add metadata
	        $grouptopic->status = $status; // the current status i.e sticky, closed, resolved, open
	           
    // now add the topic message as an annotation
        	$grouptopic->annotate('group_topic_post',$message,$access, $user);   
        	
    // add to river
	        add_to_river('river/forum/topic/create','create',$_SESSION['user']->guid,$grouptopic->guid);
	        
	// Success message
			system_message(elgg_echo("grouptopic:created"));
			
	// Forward to the group forum page
	        global $CONFIG;
	        $url = $CONFIG->wwwroot . "pg/groups/forum/{$group_guid}/";
			forward($url);
				
		}
		
?>

