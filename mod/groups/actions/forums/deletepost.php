<?php

	/**
	 * Elgg Groups: delete topic comment action
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

	// Ensure we're logged in
		if (!isloggedin()) forward();
		
  
	// Make sure we can get the comment in question
		$post_id = (int) get_input('post');
		$group_guid = (int) get_input('group');
		$topic_guid = (int) get_input('topic');
		
		if ($post = get_annotation($post_id)) {
			
			//check that the user can edit as well as admin
			if ($post->canEdit() || ($post->owner_guid == $_SESSION['user']->guid)) {
    			
    			//delete forum comment
				$post->delete();
				
				// remove river entry if it exists
				remove_from_river_by_annotation($post_id);

				//display confirmation message
				system_message(elgg_echo("grouppost:deleted"));
			}
			
		} else {
			$url = "";
			system_message(elgg_echo("grouppost:notdeleted"));
		}
		
    // Forward to the group forum page
    global $CONFIG;
	$url = $CONFIG->wwwroot . "mod/groups/topicposts.php?topic={$topic_guid}&group_guid={$group_guid}";
	forward($url);

?>