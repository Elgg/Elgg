<?php
/**
 * Invite users to join a group
 *
 * @package ElggGroups
 */

$logged_in_user = elgg_get_logged_in_user_entity();

$user_guid = get_input('user_guid');
if (!is_array($user_guid)) {
	$user_guid = array($user_guid);
}
$group_guid = get_input('group_guid');

if (sizeof($user_guid)) {
	foreach ($user_guid as $u_id) {
		$user = get_entity($u_id);
		$group = get_entity($group_guid);

		if ($user && $group && ($group instanceof ElggGroup) && $group->canEdit()) {

			if (!check_entity_relationship($group->guid, 'invited', $user->guid)) {

				// Create relationship
				add_entity_relationship($group->guid, 'invited', $user->guid);

				// Send email
				$url = elgg_normalize_url("groups/invitations/$user->username");
				$result = notify_user($user->getGUID(), $group->owner_guid,
						elgg_echo('groups:invite:subject', array($user->name, $group->name)),
						elgg_echo('groups:invite:body', array(
							$user->name,
							$logged_in_user->name,
							$group->name,
							$url,
						)),
						NULL);
				if ($result) {
					system_message(elgg_echo("groups:userinvited"));
				} else {
					register_error(elgg_echo("groups:usernotinvited"));
				}
			} else {
				register_error(elgg_echo("groups:useralreadyinvited"));
			}
		}
	}
}

forward(REFERER);
