<?php
/**
 * Invite users to join a group
 *
 * @package ElggGroups
 */

$logged_in_user = elgg_get_logged_in_user_entity();

$user_guids = get_input('user_guid');
if (!is_array($user_guids)) {
	$user_guids = array($user_guids);
}
$group_guid = get_input('group_guid');
$group = get_entity($group_guid);

if (count($user_guids) > 0 && elgg_instanceof($group, 'group') && $group->canEdit()) {
	foreach ($user_guids as $guid) {
		$user = get_user($guid);
		if (!$user) {
			continue;
		}

		if (check_entity_relationship($group->guid, 'invited', $user->guid)) {
			register_error(elgg_echo("groups:useralreadyinvited"));
			continue;
		}

		if (check_entity_relationship($user->guid, 'member', $group->guid)) {
			// @todo add error message
			continue;
		}

		// Create relationship
		add_entity_relationship($group->guid, 'invited', $user->guid);

		$url = elgg_normalize_url("groups/invitations/$user->username");

		$subject = elgg_echo('groups:invite:subject', array(
			$user->name,
			$group->name
		), $user->language);

		$body = elgg_echo('groups:invite:body', array(
			$user->name,
			$logged_in_user->name,
			$group->name,
			$url,
		), $user->language);
		
		$params = [
			'action' => 'invite',
			'object' => $group,
		];

		// Send notification
		$result = notify_user($user->getGUID(), $group->owner_guid, $subject, $body, $params);

		if ($result) {
			system_message(elgg_echo("groups:userinvited"));
		} else {
			register_error(elgg_echo("groups:usernotinvited"));
		}
	}
}

forward(REFERER);
