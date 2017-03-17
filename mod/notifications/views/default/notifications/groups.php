<?php

/**
 * Wraps forms into modules
 */
$title = elgg_echo('notifications:subscriptions:changesettings:groups');
$body = elgg_view_form('notifications/subscriptions/groups', [
	'action' => elgg_normalize_url('action/notifications/subscriptions'),
], $vars);

echo elgg_view_module('info', $title, $body, [
	'class' => 'elgg-subscription-module',
]);
