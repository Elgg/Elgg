<?php
/**
 * Elgg register form
 *
 * @package Elgg
 * @subpackage Core
 */

if (elgg_is_sticky_form('register')) {
	$values = elgg_get_sticky_values('register');

	// Add the sticky values to $vars so views extending
	// register/extend also get access to them.
	$vars = array_merge($vars, $values);

	elgg_clear_sticky_form('register');
} else {
	$values = array();
}

$password = $password2 = '';
$username = elgg_extract('username', $values, get_input('u'));
$email = elgg_extract('email', $values, get_input('e'));
$name = elgg_extract('name', $values, get_input('n'));

$fields = [
	[
		'name' => 'friend_guid',
		'type' => 'hidden',
		'value' => elgg_extract('friend_guid', $vars),
	],
	[
		'name' => 'invitecode',
		'type' => 'hidden',
		'value' => elgg_extract('invitecode', $vars),
	],
	[
		'name' => 'name',
		'type' => 'text',
		'value' => $name,
		'autofocus' => true,
		'required' => true,
		'label' => elgg_echo('name'),
		'field_class' => 'mtm',
	],
	[
		'name' => 'email',
		'type' => 'email',
		'value' => $email,
		'required' => true,
		'label' => elgg_echo('email'),
	],
	[
		'name' => 'username',
		'type' => 'text',
		'value' => $username,
		'required' => true,
		'label' => elgg_echo('username'),
	],
	[
	'name' => 'password',
		'type' => 'password',
		'value' => $password,
		'required' => true,
		'label' => elgg_echo('password'),
		],
	[
		'name' => 'password2',
		'type' => 'password',
		'value' => $password2,
		'required' => true,
		'label' => elgg_echo('passwordagain'),
	],
];

foreach ($fields as $field) {
	$type = elgg_extract('type', $field, 'text');
	unset($field[$type]);

	echo elgg_view_input($type, $field);
}

// view to extend to add more fields to the registration form
echo elgg_view('register/extend', $vars);

// Add captcha hook
echo elgg_view('input/captcha', $vars);

$footer = elgg_view_input('submit', [
	'value' => elgg_echo('register'),
]);

elgg_set_form_footer($footer);
