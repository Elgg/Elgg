<?php
	/**
	 * Join a group action.
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
				
				forward($group->getURL());
				exit;
			}
			else
				system_message(elgg_echo("groups:cantjoin"));
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
		system_message(elgg_echo("groups:cantjoin"));
		
	forward($_SERVER['HTTP_REFERER']);
	exit;
?>