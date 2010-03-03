<?php
	/**
	 * Leave a group action.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
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
		if ($group->getOwner() != $_SESSION['guid']) {
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
		
	forward($_SERVER['HTTP_REFERER']);
	exit;
?>