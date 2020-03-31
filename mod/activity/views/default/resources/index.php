<?php

if (elgg_is_logged_in()) {
	elgg_push_context('activity');
	
	// logged in users see a different output
	echo elgg_view_resource('river', $vars);
	
	elgg_pop_context();
	return;
}

$content = elgg_list_river([
	'no_results' => elgg_echo('river:none'),
]);

$login_box = elgg_view('core/account/login_box');

echo elgg_view_page(null, [
	'title' => elgg_echo('content:latest'),
	'content' => $content,
	'sidebar' => $login_box ? : false,
]);
