<?php
	/**
	 * Delete an invitation to join a closed group.
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

	$user_guid = get_input('user_guid', get_loggedin_userid());
	$group_guid = get_input('group_guid');

	$user = get_entity($user_guid);
	$group = get_entity($group_guid);

	// If join request made
			if (check_entity_relationship($group->guid, 'invited', $user->guid))
			{
				remove_entity_relationship($group->guid, 'invited', $user->guid);
				system_message(elgg_echo("groups:invitekilled"));
			}

	forward($_SERVER['HTTP_REFERER']);

?>