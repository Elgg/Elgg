<?php
	/**
	 * Delete a user request to join a closed group.
	 * 
	 * @package ElggGroups
	 */

	// Load configuration
	global $CONFIG;
	
	gatekeeper();
	
	$user_guid = get_input('user_guid', get_loggedin_userid());
	$group_guid = get_input('group_guid');
	
	$user = get_entity($user_guid);
	$group = get_entity($group_guid);
	
	// If join request made
			if (check_entity_relationship($user->guid, 'membership_request', $group->guid))
			{
				remove_entity_relationship($user->guid, 'membership_request', $group->guid);
				system_message(elgg_echo("groups:joinrequestkilled"));
			}
	
	forward($_SERVER['HTTP_REFERER']);
	
?>