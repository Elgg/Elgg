<?php
/**
 * Elgg forgotten password.
 */

$username = elgg_extract('username', $vars, '');

echo elgg_view('output/longtext', ['value' => elgg_echo('user:password:text')]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('loginusername'),
	'name' => 'username',
	'value' => $username,
	'autofocus' => empty($username),
]);

echo elgg_view_field(['#type' => 'captcha']);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('request'),
]);
elgg_set_form_footer($footer);
