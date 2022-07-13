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
	if (!$group->join($user, ['create_river_item' => true])) {
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

// Notify group owner
$owner = $group->getOwnerEntity();
$url = elgg_generate_url('requests:group:group', [
	'guid' => $group->guid,
]);

$subject = elgg_echo('groups:request:subject', [
	$user->getDisplayName(),
	$group->getDisplayName(),
], $owner->language);

$body = elgg_echo('groups:request:body', [
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
notify_user($owner->guid, $user->guid, $subject, $body, $params);

return elgg_ok_response('', elgg_echo('groups:joinrequestmade'));
