<?php
/**
 * Plugin settings for Site notifications
 */

/* @var $plugin \ElggPlugin */
$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('site_notifications:settings:unread_cleanup_days'),
	'#help' => elgg_echo('site_notifications:settings:unread_cleanup_days:help'),
	'name' => 'params[unread_cleanup_days]',
	'value' => $plugin->unread_cleanup_days,
	'min' => 0,
]);

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('site_notifications:settings:read_cleanup_days'),
	'#help' => elgg_echo('site_notifications:settings:read_cleanup_days:help'),
	'name' => 'params[read_cleanup_days]',
	'value' => $plugin->read_cleanup_days,
	'min' => 0,
]);
