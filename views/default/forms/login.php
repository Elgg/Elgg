<?php
/**
 * Elgg login form
 *
 * @package Elgg
 * @subpackage Core
 */

elgg_require_js('forms/login');

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'username',
	'autofocus' => true,
	'required' => true,
	'#label' => elgg_echo('loginusername'),
]);

echo elgg_view_field([
	'#type' => 'password',
	'name' => 'password',
	'required' => true,
	'#label' => elgg_echo('password'),
]);

echo elgg_view('login/extend', $vars);

if (isset($vars['returntoreferer'])) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'returntoreferer',
		'value' => 'true'
	]);
}

echo elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('user:persistent'),
	'name' => 'persistent',
	'value' => true,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('login'),
]);

elgg_set_form_footer($footer);
