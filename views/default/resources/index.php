<?php

if (elgg_is_logged_in()) {
	forward('activity');
}

$title = elgg_echo('content:latest');
$content = elgg_list_river();
if (!$content) {
	$content = elgg_echo('river:none');
}

$login_box = elgg_view('core/account/login_box');

$params = array(
		'title' => $title,
		'content' => $content,
		'sidebar' => $login_box
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page(null, $body);
