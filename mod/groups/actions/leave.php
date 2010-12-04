<?php
	/**
	 * Leave a group action.
	 * 
	 * @package ElggGroups
	 */

	// Load configuration
	global $CONFIG;
	
	$user_guid = get_input('user_guid');
	$group_guid = get_input('group_guid');
	
	$user = NULL;
	if (!$user_guid) $user = get_loggedin_user();
	else
		$user = get_entity($user_guid);
		
	$group = get_entity($group_guid);

	set_page_owner($group->guid);
	
	if (($user instanceof ElggUser) && ($group instanceof ElggGroup))
	{
		if ($group->getOwnerGUID() != get_loggedin_userid()) {
			if ($group->leave($user))
				system_message(elgg_echo("groups:left"));
			else
				register_error(elgg_echo("groups:cantleave"));
		} else {
			register_error(elgg_echo("groups:cantleave"));
		}
	}
	else
		register_error(elgg_echo("groups:cantleave"));
		
	forward(REFERER);
	exit;
?>