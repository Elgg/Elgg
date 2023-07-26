<?php
/**
 * Elgg register form
 */

$fields = [
	[
		'#type' => 'hidden',
		'name' => 'friend_guid',
		'value' => elgg_extract('friend_guid', $vars),
	],
	[
		'#type' => 'hidden',
		'name' => 'invitecode',
		'value' => elgg_extract('invitecode', $vars),
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('name'),
		'#class' => 'mtm',
		'name' => 'name',
		'value' => elgg_extract('name', $vars, get_input('n')),
		'autofocus' => true,
		'required' => true,
	],
	[
		'#type' => 'email',
		'#label' => elgg_echo('email'),
		'name' => 'email',
		'value' => elgg_extract('email', $vars, get_input('e')),
		'required' => true,
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('username'),
		'name' => 'username',
		'value' => elgg_extract('username', $vars, get_input('u')),
		'required' => true,
	],
	[
		'#type' => 'password',
		'#label' => elgg_echo('password'),
		'name' => 'password',
		'required' => true,
		'autocomplete' => 'new-password',
		'add_security_requirements' => true,
	],
	[
		'#type' => 'password',
		'#label' => elgg_echo('passwordagain'),
		'name' => 'password2',
		'required' => true,
		'autocomplete' => 'new-password',
		'add_security_requirements' => true,
	],
];

foreach ($fields as $field) {
	echo elgg_view_field($field);
}

// view to extend to add more fields to the registration form
echo elgg_view('register/extend', $vars);

// Add captcha
echo elgg_view('input/captcha', $vars);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('register'),
]);

elgg_set_form_footer($footer);
