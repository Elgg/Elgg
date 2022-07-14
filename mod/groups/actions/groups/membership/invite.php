<?php
/**
 * Invite users to join a group
 */

$logged_in_user = elgg_get_logged_in_user_entity();

$user_guids = get_input('user_guid');
if (!empty($user_guids) && !is_array($user_guids)) {
	$user_guids = [$user_guids];
}

if (empty($user_guids)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$group_guid = (int) get_input('group_guid');
$group = get_entity($group_guid);
if (!$group instanceof \ElggGroup || !$group->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$resend_invitation = (bool) get_input('resend');

foreach ($user_guids as $guid) {
	$user = get_user($guid);
	if (!$user instanceof \ElggUser) {
		continue;
	}

	$invite = true;
	if ($group->hasRelationship($user->guid, 'invited')) {
		// user already invited, do we need to resend the invitation
		if (!$resend_invitation) {
			elgg_register_error_message(elgg_echo('groups:useralreadyinvited'));
			continue;
		}
		$invite = false;
	}

	if ($group->isMember($user)) {
		continue;
	}

	if ($invite) {
		// Create relationship
		if (!$group->addRelationship($user->guid, 'invited')) {
			elgg_register_error_message(elgg_echo('groups:usernotinvited'));
			continue;
		}
	}

	$url = elgg_generate_url('collection:group:group:invitations', [
		'username' => $user->username,
	]);
	
	$subject = elgg_echo('groups:invite:subject', [
		$user->getDisplayName(),
		$group->getDisplayName()
	], $user->getLanguage());

	$body = elgg_echo('groups:invite:body', [
		$logged_in_user->getDisplayName(),
		$group->getDisplayName(),
		$url,
	], $user->getLanguage());
	
	$params = [
		'action' => 'invite',
		'object' => $group,
		'url' => $url,
	];

	// Send notification
	notify_user($user->guid, $group->owner_guid, $subject, $body, $params);
	
	elgg_register_success_message(elgg_echo('groups:userinvited'));
}

return elgg_ok_response();
