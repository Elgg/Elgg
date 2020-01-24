<?php
/**
 * Elgg register form
 */

if (elgg_is_sticky_form('register')) {
	$values = elgg_get_sticky_values('register');

	// Add the sticky values to $vars so views extending
	// register/extend also get access to them.
	$vars = array_merge($vars, $values);

	elgg_clear_sticky_form('register');
} else {
	$values = [];
}

$password = $password2 = '';
$username = elgg_extract('username', $values, get_input('u'));
$email = elgg_extract('email', $values, get_input('e'));
$name = elgg_extract('name', $values, get_input('n'));

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
		'value' => $name,
		'autofocus' => true,
		'required' => true,
	],
	[
		'#type' => 'email',
		'#label' => elgg_echo('email'),
		'name' => 'email',
		'value' => $email,
		'required' => true,
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('username'),
		'name' => 'username',
		'value' => $username,
		'required' => true,
	],
	[
		'#type' => 'password',
		'#label' => elgg_echo('password'),
		'name' => 'password',
		'value' => $password,
		'required' => true,
		'autocomplete' => 'new-password',
		'add_security_requirements' => true,
	],
	[
		'#type' => 'password',
		'#label' => elgg_echo('passwordagain'),
		'name' => 'password2',
		'value' => $password2,
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

// Add captcha hook
echo elgg_view('input/captcha', $vars);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('register'),
]);

elgg_set_form_footer($footer);
