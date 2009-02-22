<?php

	/**
	 * Elgg groups: add topic post action
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
	 
	// Make sure we're logged in; forward to the front page if not
		if (!isloggedin()) forward();
		
	// Check the user is a group member
	    $group_entity =  get_entity(get_input('group_guid'));
	    if (!$group_entity->isMember($vars['user'])) forward();
		
	// Get input
		$topic_guid = (int) get_input('topic_guid');
		$group_guid = (int) get_input('group_guid');
		$post = get_input('topic_post');
		
	// Let's see if we can get an entity with the specified GUID, and that it's a group forum topic
		if ($topic = get_entity($topic_guid)) {
			if ($topic->getSubtype() == "groupforumtopic") {
    			
    			//check the user posted a message
    		    if($post){
	                // If posting the comment was successful, say so
				    if ($topic->annotate('group_topic_post',$post,$topic->access_id, $_SESSION['guid'])) {
					
					    system_message(elgg_echo("groupspost:success"));
						// add to river
	        			add_to_river('river/forum/create','create',$_SESSION['user']->guid,$topic_guid);
	
				    } else {
					    system_message(elgg_echo("groupspost:failure"));
				    }
			    }else{
    			    system_message(elgg_echo("groupspost:nopost"));
			    }
			
			}
				
		} else {
		
			system_message(elgg_echo("groupstopic:notfound"));
			
		}
		
	// Forward to the group forum page
	        global $CONFIG;
	        $url = $CONFIG->wwwroot . "mod/groups/topicposts.php?topic={$topic_guid}&group_guid={$group_guid}";
	        forward($url);

?>