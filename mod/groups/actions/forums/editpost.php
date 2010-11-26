<?php

    /**
	 * Elgg groups plugin edit post action.
	 * 
	 * @package ElggGroups
	 */

		
		$group_guid = get_input('group');
	    $group_entity =  get_entity($group_guid);
	    
	//get the required variables
		$post = get_input("post");
		$field_num = get_input("field_num");
		$post_comment = get_input("postComment{$field_num}");
		$annotation = get_annotation($post);
		$commentOwner = $annotation->owner_guid;
		$access_id = $annotation->access_id;
		$topic_guid = get_input("topic");
		
		if ($annotation) {
			
			//can edit? Either the comment owner or admin can
			if (groups_can_edit_discussion($annotation, page_owner_entity()->owner_guid)) {
				
				update_annotation($post, "group_topic_post", $post_comment, "",$commentOwner, $access_id);
			    system_message(elgg_echo("groups:forumpost:edited"));
				   
			} else {
				system_message(elgg_echo("groups:forumpost:error"));
			}
			
		} else {
			
				system_message(elgg_echo("groups:forumpost:error"));
		}
		
    $topic = get_entity($topic_guid);
	forward($topic->getURL());
  
		
?>