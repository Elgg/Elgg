<?php
/**
 * Provide a way of setting your password
 *
 * @uses $vars['entity'] the user to set settings for
 */

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
if (!$user instanceof ElggUser) {
	return;
}

// only make the admin user enter current password for changing his own password.
$content = '';
if (!elgg_is_admin_logged_in() || elgg_is_admin_logged_in() && $user->guid === elgg_get_logged_in_user_guid()) {
	$content .= elgg_view_field([
		'#type' => 'password',
		'#label' => elgg_echo('user:current_password:label'),
		'name' => 'current_password',
		'autocomplete' => 'current-password',
	]);
}

$content .= elgg_view_field([
	'#type' => 'password',
	'#label' => elgg_echo('user:password:label'),
	'name' => 'password',
	'autocomplete' => 'new-password',
	'add_security_requirements' => true,
]);

$content .= elgg_view_field([
	'#type' => 'password',
	'#label' => elgg_echo('user:password2:label'),
	'name' => 'password2',
	'autocomplete' => 'new-password',
	'add_security_requirements' => true,
]);

echo elgg_view_module('info', elgg_echo('user:set:password'), $content);
