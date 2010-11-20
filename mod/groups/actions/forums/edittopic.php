<?php
/**
* Elgg groups plugin edit topic action.
 */

// Make sure we're logged in (send us to the front page if not)
if (!isloggedin()) forward();
		
// Check the user is a group member
$group_entity =  get_entity(get_input('group_guid'));
if (!$group_entity->isMember(get_loggedin_user())) forward();
     
// Get input data
$title = strip_tags(get_input('topictitle'));
$message = get_input('topicmessage');
$message_id = get_input('message_id');
$tags = get_input('topictags');
$topic_guid = get_input('topic');
$access = get_input('access_id');
$group_guid = get_input('group_guid');
$status = get_input('status'); // open, closed
		
// Convert string of tags into a preformatted array
$tagarray = string_to_tag_array($tags);
		
// Make sure we actually have permission to edit
$topic = get_entity($topic_guid);
if ($topic){
	$user = $topic->getOwnerGUID();
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
			// Set the message
			$topic->description = $message;
			// if no tags are present, clear existing ones
			if (is_array($tagarray)) {
				$topic->tags = $tagarray;
			} else $topic->clearMetadata('tags');
			// edit metadata
	       $topic->status = $status; // the current status i.e sticky, closed, resolved
				     	       
			// save the changes
			if (!$topic->save()) {
				//		register_error(elgg_echo("forumtopic:error"));
		 	}
			// Success message
			system_message(elgg_echo("groups:forumtopic:edited"));
		}
	}
}
// Forward to the discussion
global $CONFIG;
$url = elgg_get_site_url() . "mod/groups/topicposts.php?topic={$topic_guid}&group_guid={$group_guid}/";
forward($url);

