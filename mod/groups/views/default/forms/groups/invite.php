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

$friends_count = elgg_get_logged_in_user_entity()->getFriends(['count' => true]);
if (empty($friends_count)) {
	echo elgg_echo('groups:nofriendsatall');
	return;
}

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

echo elgg_view_field([
	'#type' => 'friendspicker',
	'#help' => elgg_echo('groups:invite:friends:help'),
	'name' => 'user_guid',
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('groups:invite:resend'),
	'name' => 'resend',
	'value' => 1,
	'switch' => true,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('invite'),
]);

elgg_set_form_footer($footer);
