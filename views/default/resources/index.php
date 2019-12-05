<?php

$user = elgg_get_logged_in_user_entity();

if ($user) {
	$title = elgg_echo('welcome:user', [$user->getDisplayName()]);
	$sidebar = null;
} else {
	$title = elgg_echo('welcome');
	$sidebar = elgg_view('core/account/login_box');
}

$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => elgg_echo('index:content'),
	'sidebar' => $sidebar,
]);

echo elgg_view_page(null, $body);
