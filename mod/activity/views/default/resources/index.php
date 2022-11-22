<?php

if (elgg_is_logged_in()) {
	elgg_push_context('activity');
	
	// logged in users see a different output
	echo elgg_view_resource('activity/all', $vars);
	
	elgg_pop_context();
	return;
}

if (elgg_get_config('allow_registration')) {
	$sidebar = elgg_view_module('aside', elgg_echo('register'), elgg_view_form('register', [
		'sticky_enabled' => true,
		'sticky_ignored_fields' => [
			'password',
			'password2',
		],
	]));
} else {
	$sidebar = elgg_view('core/account/login_box');
}

echo elgg_view_page('', [
	'title' => elgg_echo('content:latest'),
	'content' => elgg_view('river/listing/all'),
	'sidebar' => $sidebar,
]);
