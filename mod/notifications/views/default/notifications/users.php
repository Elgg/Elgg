<?php
/**
 * Manage subscriptions to other users for the given user
 *
 * @uses $vars['user'] The user to manage
 */

$title = elgg_echo('notifications:subscriptions:users:title');
$body = elgg_view_form('notifications/subscriptions/users', [
	'action' => elgg_normalize_url('action/notifications/subscriptions'),
], $vars);

echo elgg_view_module('info', $title, $body, [
	'class' => 'elgg-subscription-module',
]);
