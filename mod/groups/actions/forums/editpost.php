<?php

    /**
	 * Elgg groups plugin edit post action.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

    // Make sure we're logged in (send us to the front page if not)
		if (!isloggedin()) forward();
		
	// Check the user is a group member
		$group_guid = get_input('group');
	    $group_entity =  get_entity($group_guid);
	    if (!$group_entity->isMember($vars['user'])) forward();
	    
	//get the required variables
		$post = get_input("post");
		$post_comment = get_input("postComment");
		$annotation = get_annotation($post);
		$commentOwner = get_input("commentOwner");
		$access_id = get_input("access_id");
		$topic = get_input("topic");
		
		if($annotation){
			
			//can edit? Either the comment owner or admin can
			if($annotation->canedit() || ($commentOwner == $_SESSION['user']->guid)){
				
				update_annotation($post, "group_topic_post", $post_comment, "",$commentOwner, $access_id);
			    system_message(elgg_echo("groups:forumpost:edited"));
				   
			}else{
				system_message(elgg_echo("groups:forumpost:error"));
			}
			
		}else{
			
				system_message(elgg_echo("groups:forumpost:error"));
		}
		
		// Forward to the group forum page
	    global $CONFIG;
	    $url = $CONFIG->wwwroot . "mod/groups/topicposts.php?topic={$topic}&group_guid={$group_guid}/";
		forward($url);
  
		
?>