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