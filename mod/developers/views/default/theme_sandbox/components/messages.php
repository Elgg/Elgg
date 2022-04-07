<?php

echo elgg_view_message('success', 'Success message (.elgg-message-success) example with a <a href="#">link</a>.');
echo elgg_view_message('notice', 'Notice message (.elgg-message-notice) example with a <a href="#">link</a>', ['menu' => elgg_view_url('login', elgg_echo('login'))]);
echo elgg_view_message('help', 'Help message (.elgg-message-help) example with a <a href="#">link</a>', [
	'menu' => elgg_view_menu('message_temp', ['items' => [[
		'name' => 'login',
		'text' => elgg_echo('login'),
		'href' => false,
		'class' => ['elgg-button', 'elgg-button-action'],
	]]]),
]);
echo elgg_view_message('info', 'Info message (.elgg-message-info) example with a call to action', ['link' => '<a href="#">Do it!</a>']);
echo elgg_view_message('info', 'Info message (.elgg-message-info) example with a <a href="#">link</a>');
echo elgg_view_message('warning', 'Warning message (.elgg-message-warning) example with a <a href="#">link</a>');
echo elgg_view_message('error', 'Error message (.elgg-message-error) example with a <a href="#">link</a>');

echo elgg_view_message('success', 'Success message (.elgg-message-success) without title', ['title' => false]);
echo elgg_view_message('notice', 'Notice message (.elgg-message-notice) without title', ['title' => false]);
echo elgg_view_message('help', 'Help message (.elgg-message-help) without title', ['title' => false]);
echo elgg_view_message('info', 'Info message (.elgg-message-info) without title', ['title' => false]);
echo elgg_view_message('warning', 'Warning message (.elgg-message-warning) without title', ['title' => false]);
echo elgg_view_message('error', 'Error message (.elgg-message-error) without title', ['title' => false]);
