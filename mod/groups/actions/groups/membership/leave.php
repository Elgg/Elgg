<?php
/**
 * Leave a group action.
 *
 * @package ElggGroups
 */

$user_guid = (int) get_input('user_guid', elgg_get_logged_in_user_guid());
$group_guid = (int) get_input('group_guid');

$user = get_user($user_guid);
$group = get_entity($group_guid);

if (!$user || !($group instanceof \ElggGroup)) {
	return elgg_error_response(elgg_echo('groups:cantleave'));
}

if ($group->getOwnerGUID() === $user->guid) {
	// owner can't be removed
	return elgg_error_response(elgg_echo('groups:cantleave'));
}

if (!$group->leave($user)) {
	return elgg_error_response(elgg_echo('groups:cantleave'));
}

return elgg_ok_response('', elgg_echo('groups:left'));
