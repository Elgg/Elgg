<?php

$user = elgg_extract('user', $vars);
if (!$user instanceof \ElggUser) {
	return;
}

echo elgg_view('output/longtext', ['value' => elgg_echo('uservalidationbyemail:change_email:info')]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $user->guid,
]);

$change_secret = elgg_build_hmac([
	$user->guid,
	$user->time_created,
])->getToken();

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'change_secret',
	'value' => $change_secret,
]);

echo elgg_view_field([
	'#type' => 'fieldset',
	'fields' => [
		[
			'#type' => 'email',
			'#class' => 'elgg-field-stretch',
			'name' => 'email',
			'value' => $user->email,
			'required' => true,
			'placeholder' => elgg_echo('email'),
		],
		[
			'#type' => 'submit',
			'text' => elgg_echo('resend'),
		],
	],
	'align' => 'horizontal',
]);
