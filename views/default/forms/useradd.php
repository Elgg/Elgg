<?php

/**
 * Elgg add user form.
 *
 * @package Elgg
 * @subpackage Core
 *
 */
elgg_require_js('forms/useradd');

if (elgg_is_sticky_form('useradd')) {
	$values = elgg_get_sticky_values('useradd');
	elgg_clear_sticky_form('useradd');
} else {
	$values = [];
}

$password = $password2 = '';
$name = elgg_extract('name', $values);
$username = elgg_extract('username', $values);
$email = elgg_extract('email', $values);
$admin = elgg_extract('admin', $values);
$autogen_password = elgg_extract('autogen_password', $values);

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'name',
	'value' => $name,
	'#label' => elgg_echo('name'),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'username',
	'value' => $username,
	'#label' => elgg_echo('username'),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'email',
	'name' => 'email',
	'value' => $email,
	'#label' => elgg_echo('email'),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'name' => 'autogen_password',
	'value' => 1,
	'default' => false,
	'label' => elgg_echo('autogen_password_option'),
	'checked' => (bool) $autogen_password,
]);

echo elgg_view_field([
	'#type' => 'password',
	'name' => 'password',
	'value' => $password,
	'#label' => elgg_echo('password'),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'password',
	'name' => 'password2',
	'value' => $password2,
	'#label' => elgg_echo('passwordagain'),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'name' => 'admin',
	'value' => 1,
	'default' => false,
	'label' => elgg_echo('admin_option'),
	'checked' => $admin,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('register'),
]);

elgg_set_form_footer($footer);
