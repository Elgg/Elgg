<?php
/**
 * Elgg groups plugin
 *
 * @package ElggGroups
 */

$group = $vars['entity'];
$owner = get_entity($vars['entity']->owner_guid);
$forward_url = $group->getURL();
$friends = elgg_get_logged_in_user_entity()->getFriends('', 0);

if ($friends) {
	echo elgg_view('core/friends/picker', array('entities' => $friends, 'name' => 'user_guid', 'highlight' => 'all'));
	echo elgg_view('input/hidden', array('name' => 'forward_url', 'value' => $forward_url));
	echo elgg_view('input/hidden', array('name' => 'group_guid', 'value' => $group->guid));
	echo elgg_view('input/submit', array('value' => elgg_echo('invite')));
} else {
	echo elgg_echo('groups:nofriendsatall');
}