<?php

$body = elgg_view_layout('default', [
	'content' => elgg_view('core/account/login_box', ['title' => false]),
	'title' => elgg_echo('login'),
	'sidebar' => false,
]);

echo elgg_view_page('', $body, 'walled_garden');
