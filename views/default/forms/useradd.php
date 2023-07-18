<?php
/**
 * Elgg add user form
 */

elgg_require_js('forms/useradd');

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('name'),
	'name' => 'name',
	'value' => elgg_extract('name', $vars),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('username'),
	'name' => 'username',
	'value' => elgg_extract('username', $vars),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'email',
	'#label' => elgg_echo('email'),
	'name' => 'email',
	'value' => elgg_extract('email', $vars),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('autogen_password_option'),
	'name' => 'autogen_password',
	'value' => 1,
	'default' => false,
	'checked' => (bool) elgg_extract('autogen_password', $vars),
]);

echo elgg_view_field([
	'#type' => 'password',
	'#label' => elgg_echo('password'),
	'name' => 'password',
	'required' => true,
	'autocomplete' => 'new-password',
	'add_security_requirements' => true,
]);

echo elgg_view_field([
	'#type' => 'password',
	'#label' => elgg_echo('passwordagain'),
	'name' => 'password2',
	'required' => true,
	'autocomplete' => 'new-password',
	'add_security_requirements' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('admin_option'),
	'name' => 'admin',
	'value' => 1,
	'default' => false,
	'checked' => (bool) elgg_extract('admin', $vars),
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('register'),
]);

elgg_set_form_footer($footer);
