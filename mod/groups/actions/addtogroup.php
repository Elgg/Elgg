<?php

	/**
	 * Add a user to a group
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
	$group = get_entity($group_guid);

	if ($_SESSION['user']->getGUID() == $group->owner_guid)
	{
		$requests = $user->group_join_request;
		
		if ($requests)
		{
			foreach ($requests as $request) 
			{
				if ($request == $group->getGUID())
				{
					
					// User has requested to join this group previously, so we can safely add them

					// add them
					if ((!$group->isMember($user)) && ($group->join($user)))
					{
						
						// send welcome email
						notify_user($user->getGUID(), $group->owner_guid, 
							sprintf(elgg_echo('groups:welcome:subject'), $group->title), 
							sprintf(elgg_echo('groups:welcome:body'), $user->name, $group->title, $group->getURL()),
							NULL, "email");
							
						system_message(elgg_echo('groups:addedtogroup'));
						
					}
					else
						system_message(elgg_echo("groups:cantjoin"));
					
					forward($_SERVER['HTTP_REFERER']);
					exit;	
				}
			}
		}
			
		// Not found in request array, so send an invite and set invite flag
		$methods = $user->group_invite;
		if (($methods) && (!is_array($methods)))
			$methods = array($methods);
		if (!$methods) $methods=array();
		$methods[] = $group->getGUID();
		$methods = array_unique($methods);
		
		// Set invite flag
		//if (!$user->setMetaData('group_invite', $group->getGUID(), "", true))
		if (!$user->group_invite = $methods)
			system_message(elgg_echo("groups:usernotinvited"));
		else
		{
			// Send email
			if (notify_user($user->getGUID(), $group->owner_guid, 
					sprintf(elgg_echo('groups:invite:subject'), $user->name, $group->title), 
					sprintf(elgg_echo('groups:invite:body'), $user->name, $group->title, "{$CONFIG->url}action/groups/join?user_guid={$user->guid}&group_guid={$group->guid}"),
					NULL, "email"))
				system_message(elgg_echo("groups:userinvited"));
			else
				system_message(elgg_echo("groups:usernotinvited"));
		}
		
	}
	else
		system_message(elgg_echo("groups:notowner"));
		
	//forward($_SERVER['HTTP_REFERER']);
	exit;	
?>