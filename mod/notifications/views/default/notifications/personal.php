<?php

/**
 * Wraps forms into modules
 */
$title = elgg_echo('notifications:subscriptions:personal:title');
$body = elgg_view_form('notifications/settings', [], $vars);

echo elgg_view_module('info', $title, $body);

$title = elgg_echo('notifications:subscriptions:title');
$body = elgg_view_form('notifications/subscriptions/users', [
	'action' => elgg_normalize_url('action/notifications/subscriptions'),
], $vars);

echo elgg_view_module('info', $title, $body, [
	'class' => 'elgg-subscription-module',
]);
