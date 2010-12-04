<?php
/**
 * User requests to join a closed group.
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

// If not a member of this group
if (($group) && ($user) && (!$group->isMember($user)))
{
	// If open group or invite exists
	if (
		($group->isPublicMembership()) ||
		(check_entity_relationship($group->guid, 'invited', $user->guid))
	)
	{
		//$ia = elgg_set_ignore_access(TRUE);
		if ($group->join($user))
		{
			// Remove relationships
			remove_entity_relationship($group->guid, 'invited', $user->guid);
			remove_entity_relationship($user->guid, 'membership_request', $group->guid);

			// Group joined
			system_message(elgg_echo('groups:joined'));
			elgg_set_ignore_access($ia);

			forward($group->getURL());
			exit;
		}
		else {
			elgg_set_ignore_access($ia);
			system_message(elgg_echo('groups:cantjoin'));
		}
	}
	else
	{
		// If join request not already made
		if (!check_entity_relationship($user->guid, 'membership_request', $group->guid))
		{
			// Add membership requested
			add_entity_relationship($user->guid, 'membership_request', $group->guid);

			// Send email
			$url = "{$CONFIG->url}mod/groups/membershipreq.php?group_guid={$group->guid}";
			if (notify_user($group->owner_guid, $user->getGUID(),
					elgg_echo('groups:request:subject', array($user->name, $group->name)),
					elgg_echo('groups:request:body', array($group->getOwnerEntity()->name, $user->name, $group->name, $user->getURL(), $url)),
					NULL))
				system_message(elgg_echo("groups:joinrequestmade"));
			else
				register_error(elgg_echo("groups:joinrequestnotmade"));
		}
		else
			system_message(elgg_echo("groups:joinrequestmade"));
	}
}

forward(REFERER);