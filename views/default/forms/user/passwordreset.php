<?php
/**
 * Reset user password form
 */

echo autop(elgg_echo('user:resetpassword:reset_password_confirm'));

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
