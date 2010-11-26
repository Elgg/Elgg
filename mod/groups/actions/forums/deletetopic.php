<?php

	/**
	 * Elgg Groups: delete topic action
	 * 
	 * @package ElggGroups
	 */
		
	    $group_entity =  get_entity(get_input('group'));

	// Get input data
		$topic_guid = (int) get_input('topic');
		$group_guid = (int) get_input('group');
		
		$topic = get_entity($topic_guid);
		if ($topic->getSubtype() == "groupforumtopic") {

	// Make sure we actually have permission to edit
			if (!$topic->canEdit()) {
				register_error(elgg_echo("groupstopic:notdeleted"));
				forward(REFERER);
			}

		// Delete it!
				$rowsaffected = $topic->delete();
				if ($rowsaffected > 0) {
		// Success message
					system_message(elgg_echo("groupstopic:deleted"));
				} else {
					register_error(elgg_echo("groupstopic:notdeleted"));
				}
		// Forward to the group forum page
	        $url = $CONFIG->wwwroot . "pg/groups/forum/{$group_guid}/";
			forward($url);
		
		}
		
?>