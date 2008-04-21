<?php

	/**
	 * Elgg profile plugin edit action
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */
		
	// Get profile fields
		$aboutme = get_input('aboutme');
		$location = string_to_tag_array(get_input('location'));
		$skills = string_to_tag_array(get_input('skills'));
		$interests = string_to_tag_array(get_input('interests'));
		
	// Save stuff if we can, and forward to the user's profile
		$user = $_SESSION['user'];
		if ($user->canEdit()) {
			
			// Save stuff
			$user->description = $aboutme;
			$user->save();
			$user->location = $location;
			$user->skills = $skills;
			$user->interests = $interests;
			system_message(elgg_echo("profile:saved"));
			
			// Forward to the user's profile
			forward($user->getUrl());

		} else {
	// If we can't, display an error
			
			system_message(elgg_echo("profile:cantedit"));
		}

?>