<?php
	/**
	 * Join a group action.
	 *
	 * @package ElggGroups
	 */

	// Load configuration
	global $CONFIG;

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

	set_page_owner($group->guid);

	if (($user instanceof ElggUser) && ($group instanceof ElggGroup))
	{
		if ($group->isPublicMembership() || $group->canEdit($user->guid))
		{
			if ($group->join($user))
			{
				system_message(elgg_echo("groups:joined"));

				// Remove any invite or join request flags
				remove_entity_relationship($group->guid, 'invited', $user->guid);
				remove_entity_relationship($user->guid, 'membership_request', $group->guid);

				// add to river
				add_to_river('river/relationship/member/create','join',$user->guid,$group->guid);

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
			forward(elgg_add_action_tokens_to_url("action/groups/joinrequest?user_guid=$user_guid&group_guid=$group_guid", FALSE));
			exit;
		}
	}
	else
		register_error(elgg_echo("groups:cantjoin"));

	forward(REFERER);
	exit;
?>
