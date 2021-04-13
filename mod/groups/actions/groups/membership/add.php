<?php
/**
 * Add users to a group
 */

$user_guid = get_input('user_guid');
if (!is_array($user_guid)) {
	$user_guid = [$user_guid];
}

$group_guid = (int) get_input('group_guid');
$group = get_entity($group_guid);
if (!($group instanceof \ElggGroup) || !$group->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

if (empty($user_guid)) {
	return elgg_ok_response();
}

$errors = [];

foreach ($user_guid as $u_guid) {
	$user = get_user($u_guid);
	if (empty($user)) {
		continue;
	}
	
	if ($group->isMember($user)) {
		$errors[] = elgg_echo('groups:add:alreadymember', [$user->getDisplayName()]);
		
		// if an invitation is still pending clear it up, we don't need it
		remove_entity_relationship($group->guid, 'invited', $user->guid);
		
		// if a membership request is still pending clear it up, we don't need it
		remove_entity_relationship($user->guid, 'membership_request', $group->guid);
		
		continue;
	}
	
	if (!$group->join($user, ['create_river_item' => true])) {
		$errors[] = elgg_echo('groups:error:addedtogroup', [$user->getDisplayName()]);
		
		continue;
	}
	
	$subject = elgg_echo('groups:welcome:subject', [$group->getDisplayName()], $user->language);

	$body = elgg_echo('groups:welcome:body', [
		$group->getDisplayName(),
		$group->getURL(),
	], $user->language);
	
	$params = [
		'action' => 'add_membership',
		'object' => $group,
		'url' => $group->getURL(),
	];

	// Send welcome notification to user
	notify_user($user->getGUID(), $group->owner_guid, $subject, $body, $params);

	system_message(elgg_echo('groups:addedtogroup'));
}

if ($errors) {
	foreach ($errors as $error) {
		register_error($error);
	}
}

return elgg_ok_response();
