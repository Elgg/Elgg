<?php
/**
 * Remove a user from a group
 *
 * @package ElggGroups
 */

$user_guid = (int) get_input('user_guid');
$group_guid = (int) get_input('group_guid');

$user = get_user($user_guid);
$group = get_entity($group_guid);

if (!$user || !($group instanceof \ElggGroup) || !$group->canEdit()) {
	return elgg_error_response(elgg_echo('groups:cantremove'));
}

if ($group->getOwnerGUID() === $user->guid) {
	// owner can't be removed
	return elgg_error_response(elgg_echo('groups:cantremove'));
}

if (!$group->leave($user)) {
	return elgg_error_response(elgg_echo('groups:cantremove'));
}

return elgg_ok_response('', elgg_echo('groups:removed', [$user->getDisplayName()]));
