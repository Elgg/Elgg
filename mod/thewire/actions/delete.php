<?php

	/**
	 * Elgg thewire: delete note action
	 * 
	 * @package ElggTheWire
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

				forward("pg/thewire/owner/{$owner->username}");
		}
		
?>