<?php

	/**
	 * Elgg Groups: delete topic action
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

	// Make sure we're logged in; forward to the front page if not
		if (!isloggedin()) forward();
		
	// Check the user is a group member
	    $group_entity =  get_entity(get_input('group'));
	    if (!$group_entity->isMember($vars['user'])) forward();

	// Get input data
		$topic_guid = (int) get_input('topic');
		$group_guid = (int) get_input('group');
		
	// Make sure we actually have permission to edit
		$topic = get_entity($topic_guid);
		if ($topic->getSubtype() == "groupforumtopic") {
	
		// Get owning user
			//	$owner = get_entity($topic->getOwner());
		// Delete it!
				$rowsaffected = $topic->delete();
				if ($rowsaffected > 0) {
		// Success message
					system_message(elgg_echo("groupstopic:deleted"));
				} else {
					system_message(elgg_echo("groupstopic:notdeleted"));
				}
		// Forward to the group forum page
	        global $CONFIG;
	        $url = $CONFIG->wwwroot . "pg/groups/forum/{$group_guid}/";
			forward($url);
		
		}
		
?>