<?php
/**
 * Manage notification settings for a given user
 *
 * @uses #vars['user'] The user to manage
 */

$title = elgg_echo('notifications:settings:title');
$body = elgg_view_form('notifications/settings', [], $vars);

echo elgg_view_module('info', $title, $body);
