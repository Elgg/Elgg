<?php

if (elgg_is_logged_in()) {
	$title = elgg_echo('welcome:user', [elgg_get_logged_in_user_entity()->getDisplayName()]);
	$content = elgg_echo('index:content');
} elseif (elgg_get_config('allow_registration')) {
	$title = elgg_echo('register');
	$content = elgg_view_form('register', ['ajax' => true]);
} else {
	$title = elgg_echo('login');
	$content = elgg_view_form('login', ['ajax' => true]);
}

echo elgg_view_page(null, [
	'title' => $title,
	'content' => $content,
	'filter_id' => 'index',
]);
