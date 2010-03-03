<?php
	/**
	 * Join a group action.
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

	// @todo fix for #287
	// disable access to get entity.
	$invitations = groups_get_invited_groups($user_guid, TRUE);

	if (in_array($group_guid, $invitations)) {
		$ia = elgg_set_ignore_access(TRUE);
	}

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
				remove_entity_relationship($group->guid, 'invited', $user->guid);
				remove_entity_relationship($user->guid, 'membership_request', $group->guid);

				// add to river
				add_to_river('river/group/create','join',$user->guid,$group->guid);

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
			forward(elgg_add_action_tokens_to_url($CONFIG->url . "action/groups/joinrequest?user_guid=$user_guid&group_guid=$group_guid"));
			exit;
		}
	}
	else
		register_error(elgg_echo("groups:cantjoin"));

	forward($_SERVER['HTTP_REFERER']);
	exit;
?>
