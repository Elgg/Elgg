<?php
/**
 * Provide a way of changing the username of a user
 *
 * @uses $vars['entity'] the user to set settings for
 */

if (!elgg_is_admin_logged_in() && !elgg_get_config('can_change_username')) {
	// only admins are allowed to change the username
	return;
}

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
if (!$user instanceof \ElggUser) {
	return;
}

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('username'),
	'#help' => elgg_echo('user:username:help'),
	'name' => 'username',
	'value' => $user->username,
]);
