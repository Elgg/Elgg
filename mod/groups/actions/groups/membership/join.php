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

$user = get_entity($user_guid, true);
$group = get_entity($group_guid, true);

if (!$user instanceof ElggUser || !$group instanceof ElggGroup) {
	register_error(elgg_echo("groups:cantjoin"));
	forward(REFERRER);
}

$can_join = function() use ($user, $group) {
	if ($group->isPublicMembership()) {
		// any user is allowed to join a public groups
		return true;
	}
	if ($group->canEdit($user->guid)) {
		// anyone with edit permissions (site admins) join any group
		return true;
	}
	if (check_entity_relationship($group->guid, 'invited', $user->guid)) {
		// user has a pending group invitation
		return true;
	}
	return false;
};

if ($can_join()) {
	// attempt to join a group
	if (groups_join_group($group, $user)) {
		system_message(elgg_echo("groups:joined"));
		forward($group->getURL());
	}
	register_error(elgg_echo("groups:cantjoin"));
	forward(REFERRER);
}

if (check_entity_relationship($user->guid, 'membership_request', $group->guid)) {
	register_error(elgg_echo("groups:requestexists"));
	forward(REFERER);
}

// create a membership request if the group is closed
// or there is no pending invitation
$requested = add_entity_relationship($user->guid, 'membership_request', $group->guid);

if (!$requested) {
	register_error(elgg_echo("groups:joinrequestnotmade"));
	forward(REFERRER);
}

system_message(elgg_echo("groups:joinrequestmade"));

// @todo: in 3.0, move this to a 'create', 'relationship' event listener
$notify_owner = function() use ($user, $group) {
	$owner = get_entity($group->owner_guid, true);

	if (!$owner) {
		return false;
	}

	$url = elgg_normalize_url("groups/requests/$group->guid");

	$subject = elgg_echo('groups:request:subject', array(
		$user->name,
		$group->name,
	), $owner->language);

	$body = elgg_echo('groups:request:body', array(
		$group->getOwnerEntity()->name,
		$user->name,
		$group->name,
		$user->getURL(),
		$url,
	), $owner->language);

	$params = [
		'action' => 'membership_request',
		'object' => $group,
	];
	
	return notify_user($owner->guid, $user->getGUID(), $subject, $body, $params);
};

$notify_owner();
