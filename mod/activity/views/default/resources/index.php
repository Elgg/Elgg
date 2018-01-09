<?php

if (elgg_is_logged_in()) {
	forward(elgg_generate_url('default:river'));
}

$title = elgg_echo('content:latest');
$content = elgg_list_river([
	'no_results' => elgg_echo('river:none'),
]);

$login_box = elgg_view('core/account/login_box');

$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'sidebar' => $login_box ? : false,
]);

echo elgg_view_page(null, $body);
