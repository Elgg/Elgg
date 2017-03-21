<?php
/**
 * Elgg groups invite form
 *
 * @package ElggGroups
 */

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

if (!elgg_is_logged_in()) {
	return;
}

$friends = elgg_get_logged_in_user_entity()->getFriends(['limit' => 0]);

if (empty($friends)) {
	echo elgg_echo('groups:nofriendsatall');
	return;
}

echo elgg_view_field([
	'#type' => 'friendspicker',
	'entities' => $friends,
	'name' => 'user_guid',
	'highlight' => 'all',
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'forward_url',
	'value' => $group->getURL(),
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'group_guid',
	'value' => $group->guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('invite'),
]);

elgg_set_form_footer($footer);
