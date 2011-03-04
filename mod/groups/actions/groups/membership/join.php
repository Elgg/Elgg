<?php
/**
 * Join a group
 *
 * Three states:
 * open group so user joins
 * closed group so request sent to group owner
 * closed group with invite so user joins
 * 
 * @package ElggGroups
 */

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$group_guid = get_input('group_guid');

$user = get_entity($user_guid);

// access bypass for getting invisible group
$ia = elgg_set_ignore_access(true);
$group = get_entity($group_guid);
elgg_set_ignore_access($ia);

if (($user instanceof ElggUser) && ($group instanceof ElggGroup)) {

	// join or request
	$join = false;
	if ($group->isPublicMembership() || $group->canEdit($user->guid)) {
		// anyone can join public groups and admins can join any group
		$join = true;
	} else {
		if (check_entity_relationship($group->guid, 'invited', $user->guid)) {
			// user has invite to closed group
			$join = true;
		}
	}

	if ($join) {
		if (groups_join_group($group, $user)) {
			system_message(elgg_echo("groups:joined"));
			forward($group->getURL());
		} else {
			register_error(elgg_echo("groups:cantjoin"));
		}
	} else {
		add_entity_relationship($user->guid, 'membership_request', $group->guid);

		// Notify group owner
		$url = "{$CONFIG->url}mod/groups/membershipreq.php?group_guid={$group->guid}";
		$subject = elgg_echo('groups:request:subject', array(
			$user->name,
			$group->name,
		));
		$body = elgg_echo('groups:request:body', array(
			$group->getOwnerEntity()->name,
			$user->name,
			$group->name,
			$user->getURL(),
			$url,
		));
		if (notify_user($group->owner_guid, $user->getGUID(), $subject, $body)) {
			system_message(elgg_echo("groups:joinrequestmade"));
		} else {
			register_error(elgg_echo("groups:joinrequestnotmade"));
		}
	}
} else {
	register_error(elgg_echo("groups:cantjoin"));
}

forward(REFERER);
