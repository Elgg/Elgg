<?php
/**
 * Reset user password form
 */

echo elgg_view('output/longtext', array('value' => elgg_echo('user:resetpassword:reset_password_confirm')));

echo elgg_view('input/hidden', array(
	'name' => 'u',
	'value' => $vars['guid'],
));

echo elgg_view('input/hidden', array(
	'name' => 'c',
	'value' => $vars['code'],
));

echo elgg_view('input/submit', array(
	'value' => elgg_echo('resetpassword')
));
