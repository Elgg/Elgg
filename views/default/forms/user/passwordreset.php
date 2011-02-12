<?php

$user = $vars['user'];

echo elgg_echo('user:resetpassword:reset_password_confirm') . "<br />";

echo elgg_view('input/hidden', array(
	'internalname' => 'u',
	'value' => $user->guid
));

echo elgg_view('input/hidden', array(
	'internalname' => 'c',
	'value' => $code
));

echo elgg_view('input/submit', array(
	'value' => elgg_echo('resetpassword')
));