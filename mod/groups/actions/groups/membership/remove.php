<?php
/**
 * Remove a user from a group
 *
 * @package ElggGroups
 */

$user_guid = get_input('user_guid');
$group_guid = get_input('group_guid');

$user = get_entity($user_guid);
$group = get_entity($group_guid);

elgg_set_page_owner_guid($group->guid);

if (($user instanceof ElggUser) && ($group instanceof ElggGroup) && $group->canEdit()) {
	// Don't allow removing group owner
	if ($group->getOwnerGUID() != $user->getGUID()) {
		if ($group->leave($user)) {
			system_message(elgg_echo("groups:removed", array($user->name)));
		} else {
			register_error(elgg_echo("groups:cantremove"));
		}
	} else {
		register_error(elgg_echo("groups:cantremove"));
	}
} else {
	register_error(elgg_echo("groups:cantremove"));
}

forward(REFERER);
