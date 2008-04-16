<?php

	/**
	 * Generic entity viewer
	 * Given a GUID, this page will try and display any entity
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

	// Get the GUID of the entity we want to view
		$guid = (int) get_input('guid');
		
	// Get the entity, if possible
		if ($entity = get_entity($guid)) {

	// Set the body to be the full view of the entity, and the title to be its title
			$body = elgg_view_entity($entity,"",true);
			
	// Otherwise?
		} else {
			
			$body = elgg_echo('notfound');
			
		}
		
	// Display the page
		page_draw("", $body);

?>