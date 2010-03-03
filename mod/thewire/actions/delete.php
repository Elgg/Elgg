<?php

	/**
	 * Elgg thewire: delete note action
	 * 
	 * @package ElggTheWire
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

	// Make sure we're logged in (send us to the front page if not)
		if (!isloggedin()) forward();

	// Get input data
		$guid = (int) get_input('thewirepost');
		
	// Make sure we actually have permission to edit
		$thewire = get_entity($guid);
		if ($thewire->getSubtype() == "thewire" && $thewire->canEdit()) {
	
		// Get owning user
				$owner = get_entity($thewire->getOwner());
		// Delete it!
				$rowsaffected = $thewire->delete();
				if ($rowsaffected > 0) {
		// Success message
					system_message(elgg_echo("thewire:deleted"));
				} else {
					register_error(elgg_echo("thewire:notdeleted"));
				}
		// Forward to the main wire page
				forward("mod/thewire/?username=" . $owner->username);
		
		}
		
?>