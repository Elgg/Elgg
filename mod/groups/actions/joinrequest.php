<?php
	/**
	 * User requests to join a closed group.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Load configuration
	global $CONFIG;
	
	gatekeeper();
	
	$user_guid = get_input('user_guid');
	$group_guid = get_input('group_guid');
	
	$user = NULL;
	if (!$user_guid) $user = $_SESSION['user'];
	else
		$user = get_entity($user_guid);
		
	$group = get_entity($group_guid);
	
	if (!$group->isMember($user))
	{ 
		$invites = $user->group_invite;

		if ($invites)
		{
			if (!is_array($invites))
				$invites = array($invites);
			
			foreach ($invites as $invite)
			{
				if ($invite == $group->getGUID())
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
		}
	
		// else email membership requiest
		// set flag
		
		// Permit multiple values
		$methods = $user->group_join_request;
		if (($methods) && (!is_array($methods)))
			$methods = array($methods);
		if (!$methods) $methods=array();
		$methods[] = $group->getGUID();
		$methods = array_unique($methods);
		
		//if (!$user->setMetaData('group_join_request', $group->getGUID(), "", true))
		if (!$user->group_join_request = $methods)
			system_message(elgg_echo("groups:joinrequestnotmade"));
		else
		{
		
			// Send email
			if (notify_user($group->owner_guid, $user->getGUID(), 
					sprintf(elgg_echo('groups:request:subject'), $user->name, $group->name), 
					sprintf(elgg_echo('groups:request:body'), $group->getOwnerEntity()->name, $user->name, $group->name, $user->getURL(), "{$CONFIG->url}action/groups/addtogroup?user_guid={$user->guid}&group_guid={$group->guid}"),
					NULL, "email"))
				system_message(elgg_echo("groups:joinrequestmade"));
			else
				register_error(elgg_echo("groups:joinrequestnotmade"));
		}
	
	
	}
	else
		register_error(elgg_echo('groups:alreadymember'));
		
	forward($_SERVER['HTTP_REFERER']);
	exit;	
?>