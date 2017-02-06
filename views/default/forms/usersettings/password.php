<?php
/**
 * Provide a way of setting your password
 */
$user = elgg_extract('entity', $vars);
if (!($user instanceof ElggUser)) {
	return;
}
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $user->guid,
]);
// only make the admin user enter current password for changing his own password.
if (!elgg_is_admin_logged_in() || elgg_is_admin_logged_in() && $user->guid == elgg_get_logged_in_user_guid()) {
	echo elgg_view_field([
		'#type' => 'password',
		'#label' => elgg_echo('user:current_password:label'),
		'name' => 'current_password',
		'required' => true,
        'autofocus' =>true,
	]);
}
echo elgg_view_field([
	'#type' => 'password',
	'#label' => elgg_echo('user:password:label'),
	'name' => 'password',
	'required' => true,
]);
echo elgg_view_field([
	'#type' => 'password',
	'#label' => elgg_echo('user:password2:label'),
	'name' => 'password2',
	'required' => true,
]);
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);