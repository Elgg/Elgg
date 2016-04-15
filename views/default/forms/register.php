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
	$values = [];
}

echo elgg_view_input('text', [
	'name' => 'name',
	'value' => elgg_extract('name', $values, get_input('n')),
	'label' => elgg_echo('name'),
	'help' => elgg_echo('forms:register:name:help'),
	'autofocus' => true,
	'required' => true,
	'field_class' => 'mtm',
]);

echo elgg_view_input('text', [
	'name' => 'email',
	'value' => elgg_extract('email', $values, get_input('e')),
	'label' => elgg_echo('email'),
	'help' => elgg_echo('forms:register:email:help'),
	'required' => true,
]);

echo elgg_view_input('text', [
	'name' => 'username',
	'value' => elgg_extract('username', $values, get_input('u')),
	'label' => elgg_echo('username'),
	'help' => elgg_echo('forms:register:username:help'),
	'required' => true,
]);

echo elgg_view_input('password', [
	'name' => 'password',
	'value' => '',
	'label' => elgg_echo('password'),
	'help' => elgg_echo('forms:register:password:help'),
	'required' => true,
]);

echo elgg_view_input('password', [
	'name' => 'password2',
	'value' => '',
	'label' => elgg_echo('passwordagain'),
	'help' => elgg_echo('forms:register:password2:help'),
	'required' => true,
]);

// view to extend to add more fields to the registration form
echo elgg_view('register/extend', $vars);

// Add captcha hook
echo elgg_view('input/captcha', $vars);

echo '<div class="elgg-foot">';
echo elgg_view_input('hidden', [
	'name' => 'friend_guid', 
	'value' => $vars['friend_guid'],
]);
echo elgg_view_input('hidden', [
	'name' => 'invitecode', 
	'value' => $vars['invitecode'],
]);
echo elgg_view_input('submit', [
	'name' => 'submit', 
	'value' => elgg_echo('register'),
]);
echo '</div>';
