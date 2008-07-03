<?php
	/**
	 * User requests to join a closed group.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Load configuration
	global $CONFIG;
	
	gatekeeper();
	
	$user_guid = get_input('user_guid');
	$group_guid = get_input('group_guid');
	
	$user = get_entity($user_guid);	
	$group = get_entity($group);
	
	if (!$group->isMember($user))
	{
		$invites = $user->group_invite;
		
		if ($invites)
		{
			foreach ($invites as $invite)
			{
				if ($invite = $group->getGUID())
				{
					if ($group->join($user))
					{
						system_message(elgg_echo('groups:joined'));
						
						forward($group->getURL());
						exit;
					}
					else
						system_message(elgg_echo('groups:cantjoin'));
						
					forward($_SERVER['HTTP_REFERER']);
					exit;	
				}
			
			}
			
			// else email membership requiest
			// set flag
			
			if (!$user->setMetaData('group_join_request', $group->getGUID(), "", true))
				system_message(elgg_echo("groups:joinrequestnotmade"));
			else
			{
				// Send email
				if (notify_user($group->owner_guid, "", 
						sprintf(elgg_echo('groups:request:subject'), $user->name, $group->title), 
						sprintf(elgg_echo('groups:request:body'), $group->getOwner()->name, $user->name, $group->title, $user->getURL(), "http://{$CONFIG->url}action/groups/addtogroup?user_guid={$user->guid}&group_guid={$group->guid}"),
						NULL, "email"))
					system_message(elgg_echo("groups:joinrequestmade"));
				else
					system_message(elgg_echo("groups:joinrequestnotmade"));
			}
		}
		
	}
	else
		system_message(elgg_echo('groups:alreadymember'));
		
	forward($_SERVER['HTTP_REFERER']);
	exit;	
?>