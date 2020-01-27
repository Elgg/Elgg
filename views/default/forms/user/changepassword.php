<?php
/**
 * Reset user password form
 */

echo elgg_view('output/longtext', [
	'value' => elgg_echo('user:changepassword:change_password_confirm'),
]);

$fields = [
	[
		'#type' => 'hidden',
		'name' => 'u',
		'value' => elgg_extract('guid', $vars),
	],
	[
		'#type' => 'hidden',
		'name' => 'c',
		'value' => elgg_extract('code', $vars),
	],
	[
		'#type' => 'password',
		'#label' => elgg_echo('user:password:label'),
		'name' => 'password1',
		'autocomplete' => 'new-password',
		'add_security_requirements' => true,
	],
	[
		'#type' => 'password',
		'#label' => elgg_echo('user:password2:label'),
		'name' => 'password2',
		'autocomplete' => 'new-password',
		'add_security_requirements' => true,
	],
];

foreach ($fields as $field) {
	echo elgg_view_field($field);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('changepassword'),
]);
elgg_set_form_footer($footer);
