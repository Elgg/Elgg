<?php
/**
 * Add users to a group
 *
 * @package ElggGroups
 */
$logged_in_user = elgg_get_logged_in_user_entity();

$user_guid = get_input('user_guid');
if (!is_array($user_guid)) {
	$user_guid = array($user_guid);
}
$group_guid = get_input('group_guid');
$group = get_entity($group_guid);
/* @var ElggGroup $group */

$errors = array();
if (sizeof($user_guid)) {
	foreach ($user_guid as $u_guid) {
		$user = get_user($u_guid);

		if ($user && elgg_instanceof($group, 'group') && $group->canEdit()) {
			if (!$group->isMember($user)) {
				if (groups_join_group($group, $user)) {

					$subject = elgg_echo('groups:welcome:subject', array($group->name), $user->language);

					$body = elgg_echo('groups:welcome:body', array(
						$user->name,
						$group->name,
						$group->getURL(),
					), $user->language);

					// Send welcome notification to user
					notify_user($user->getGUID(), $group->owner_guid, $subject, $body);

					system_message(elgg_echo('groups:addedtogroup'));
				}
				else {
					$errors[] = elgg_echo('groups:error:addedtogroup', array($user->name));
				}
			}
			else {
				$errors[] = elgg_echo('groups:add:alreadymember', array($user->name));

				// if an invitation is still pending clear it up, we don't need it
				remove_entity_relationship($group->guid, 'invited', $user->guid);
			}
		}
	}
}

if ($errors) {
	foreach ($errors as $error) {
		register_error($error);
	}
}

forward(REFERER);
