<?php

	/**
	 * Elgg profile plugin edit action
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
		
	// Load configuration
		global $CONFIG;
		
		gatekeeper();
		action_gatekeeper();

	// Get profile fields
		$input = array();
		$accesslevel = get_input('accesslevel');
		if (!is_array($accesslevel)) $accesslevel = array();
		
		foreach($CONFIG->profile as $shortname => $valuetype) {
			$input[$shortname] = get_input($shortname);
			
			if ($valuetype == 'tags')
				$input[$shortname] = string_to_tag_array($input[$shortname]);
		}
		
	// Save stuff if we can, and forward to the user's profile
		
		if ($user = page_owner()) {
			$user = page_owner_entity();			
		} else {
			$user = $_SESSION['user'];
			set_page_owner($user->getGUID());
		}
		if ($user->canEdit()) {
			
			// Save stuff
			if (sizeof($input) > 0)
				foreach($input as $shortname => $value) {
					
					//$user->$shortname = $value;
					remove_metadata($user->guid, $shortname);
					if (isset($accesslevel[$shortname])) {
						$access_id = (int) $accesslevel[$shortname];
					} else {
						// this should never be executed since the access level should always be set
						$access_id = ACCESS_PRIVATE;
					}
					if (is_array($value)) {
						$i = 0;
						foreach($value as $interval) {
							$i++;
							if ($i == 1) { $multiple = false; } else { $multiple = true; }
							create_metadata($user->guid, $shortname, $interval, 'text', $user->guid, $access_id, $multiple);
						}
					} else {
						create_metadata($user->guid, $shortname, $value, 'text', $user->guid, $access_id);
					}
					
				}
			$user->save();

			// Notify of profile update
			trigger_elgg_event('profileupdate',$user->type,$user);
			
			//add to river
			add_to_river('river/user/default/profileupdate','update',$_SESSION['user']->guid,$_SESSION['user']->guid);
			
			system_message(elgg_echo("profile:saved"));
			
			// Forward to the user's profile
			forward($user->getUrl());

		} else {
	// If we can't, display an error
			
			system_message(elgg_echo("profile:cantedit"));
		}

?>