<?php
	/**
	 * Join a group action.
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
		
	if (($user instanceof ElggUser) && ($group instanceof ElggGroup))
	{
		if ($group->isPublicMembership())
		{
			if ($group->join($user))
			{
				system_message(elgg_echo("groups:joined"));
				
				// Remove any invite or join request flags
				remove_metadata($user->guid, 'group_invite', $group->guid);
				remove_metadata($user->guid, 'group_join_request', $group->guid);
				
				forward($group->getURL());
				exit;
			}
			else
				register_error(elgg_echo("groups:cantjoin"));
		}
		else
		{
			// Closed group, request membership
			system_message(elgg_echo('groups:privategroup'));
			forward($CONFIG->url . "actions/groups/joinrequest?user_guid=$user_guid&group_guid=$group_guid");
			exit;
		}
	}
	else
		register_error(elgg_echo("groups:cantjoin"));
		
	forward($_SERVER['HTTP_REFERER']);
	exit;
?>