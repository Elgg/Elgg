<?php

	/**
	 * Invite a user to join a group
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Load configuration
	global $CONFIG;
	
	gatekeeper();
	
	$user_guid = get_input('user_guid');
	if (!is_array($user_guid))
		$user_guid = array($user_guid);
	$group_guid = get_input('group_guid');
	
	if (sizeof($user_guid))
	{
		foreach ($user_guid as $u_id)
		{
			$user = get_entity($u_id);
			$group = get_entity($group_guid);
			
			if ( $user && $group) {
				
				if (get_loggedin_userid() == $group->owner_guid)
				{
					if (!check_entity_relationship($group->guid, 'invited', $user->guid))
					{
						if ($user->isFriend())
						{
							
							// Create relationship
							add_entity_relationship($group->guid, 'invited', $user->guid);
							
							// Send email
							if (notify_user($user->getGUID(), $group->owner_guid, 
									sprintf(elgg_echo('groups:invite:subject'), $user->name, $group->name), 
									sprintf(elgg_echo('groups:invite:body'), $user->name, $group->name, "{$CONFIG->url}action/groups/join?user_guid={$user->guid}&group_guid={$group->guid}"),
									NULL))
								system_message(elgg_echo("groups:userinvited"));
							else
								register_error(elgg_echo("groups:usernotinvited"));
							
						}
						else
							register_error(elgg_echo("groups:usernotinvited"));
					}
					else
						register_error(elgg_echo("groups:useralreadyinvited"));
				}
				else
					register_error(elgg_echo("groups:notowner"));
			}
		}
	}
	
	forward($_SERVER['HTTP_REFERER']);
	
?>