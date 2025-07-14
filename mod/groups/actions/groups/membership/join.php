<?php
/**
 * Join a group
 *
 * Three states:
 * open group so user joins
 * closed group so request sent to group owner
 * closed group with invite so user joins
 */

$user_guid = (int) get_input('user_guid', elgg_get_logged_in_user_guid());
$group_guid = (int) get_input('group_guid');

$user = get_user($user_guid);

// access bypass for getting invisible group
$group = elgg_call(ELGG_IGNORE_ACCESS, function() use ($group_guid) {
	return get_entity($group_guid);
});

if (!$user instanceof \ElggUser || !$group instanceof \ElggGroup) {
	return elgg_error_response(elgg_echo('groups:cantjoin'));
}

if (!$user->canEdit() && !$group->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

// join or request
$join = false;
if ($group->isPublicMembership() || $group->canEdit($user->guid)) {
	// anyone can join public groups and admins can join any group
	$join = true;
} else {
	if ($group->hasRelationship($user->guid, 'invited')) {
		// user has invite to closed group
		$join = true;
	}
}

if ($join) {
	if (!$group->join($user, [
		'create_river_item' => true,
		'notify_user_action' => 'join_membership',
	])) {
		return elgg_error_response(elgg_echo('groups:cantjoin'));
	}
	
	return elgg_ok_response('', elgg_echo('groups:joined'), $group->getURL());
}

if ($user->hasRelationship($group->guid, 'membership_request')) {
	return elgg_error_response(elgg_echo('groups:joinrequest:exists'));
}

if (!$user->addRelationship($group->guid, 'membership_request')) {
	return elgg_error_response(elgg_echo('groups:joinrequestnotmade'));
}

return elgg_ok_response('', elgg_echo('groups:joinrequestmade'));
