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

$user_guid = (int) get_input('user_guid', elgg_get_logged_in_user_guid());
$group_guid = (int) get_input('group_guid');

$user = get_user($user_guid);

// access bypass for getting invisible group
$ia = elgg_set_ignore_access(true);
$group = get_entity($group_guid);
elgg_set_ignore_access($ia);

if (!$user || !($group instanceof \ElggGroup)) {
	return elgg_error_response(elgg_echo('groups:cantjoin'));
}

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
	if (!$group->join($user, ['create_river_item' => true])) {
		return elgg_error_response(elgg_echo('groups:cantjoin'));
	}
	
	return elgg_ok_response('', elgg_echo('groups:joined'), $group->getURL());
}

if (check_entity_relationship($user->guid, 'membership_request', $group->guid)) {
	return elgg_error_response(elgg_echo('groups:joinrequest:exists'));
}


add_entity_relationship($user->guid, 'membership_request', $group->guid);

$owner = $group->getOwnerEntity();

$url = elgg_normalize_url("groups/requests/{$group->guid}");

$subject = elgg_echo('groups:request:subject', [
	$user->getDisplayName(),
	$group->getDisplayName(),
], $owner->language);

$body = elgg_echo('groups:request:body', [
	$group->getOwnerEntity()->getDisplayName(),
	$user->getDisplayName(),
	$group->getDisplayName(),
	$user->getURL(),
	$url,
], $owner->language);

$params = [
	'action' => 'membership_request',
	'object' => $group,
	'url' => $url,
];

// Notify group owner
if (!notify_user($owner->guid, $user->getGUID(), $subject, $body, $params)) {
	return elgg_error_response(elgg_echo('groups:joinrequestnotmade'));
}

return elgg_ok_response('', elgg_echo('groups:joinrequestmade'));
