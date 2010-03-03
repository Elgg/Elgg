<?php

	/**
	 * Elgg site message: delete
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

	// Make sure we're logged in (send us to the front page if not)
		gatekeeper();

	// Get input data
		$guid = (int) get_input('message');
		
	// Make sure we actually have permission to edit
		$message = get_entity($guid);
		if ($message->getSubtype() == "sitemessage" && $message->canEdit()) {
	
		// Delete it!
				$rowsaffected = $message->delete();
				if ($rowsaffected > 0) {
		// Success message
					system_message(elgg_echo("sitemessage:deleted"));
				} else {
					register_error(elgg_echo("sitemessage:notdeleted"));
				}
		// Forward to the river
				forward("mod/riverdashboard/");
		
		}
		
?>