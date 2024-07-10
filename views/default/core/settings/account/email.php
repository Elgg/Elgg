<?php
/**
 * Provide a way of setting your email
 *
 * @uses $vars['entity'] the user to set settings for
 */

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
if (!$user instanceof \ElggUser) {
	return;
}

$fields = [];

if (elgg_get_config('security_email_require_password') && ($user->guid === elgg_get_logged_in_user_guid())) {
	// user needs to provide current password in order to be able to change his/her email address
	$fields[] = [
		'#type' => 'password',
		'#label' => elgg_echo('email:address:password'),
		'#help' => elgg_echo('email:address:password:help'),
		'name' => 'email_password',
		'autocomplete' => 'current-password',
	];
}

$email_help = null;
if (elgg_get_config('security_email_require_confirmation') && isset($user->new_email)) {
	$email_help = elgg_echo('email:address:help:confirm', [$user->new_email]);
}

$fields[] = [
	'#type' => 'email',
	'#label' => elgg_echo('email:address:label'),
	'#help' => $email_help,
	'name' => 'email',
	'value' => $user->email,
];

if (count($fields) === 1) {
	echo elgg_view_field($fields[0]);
	return;
}

echo elgg_view_field([
	'#type' => 'fieldset',
	'legend' => elgg_echo('email:settings'),
	'fields' => $fields,
]);
