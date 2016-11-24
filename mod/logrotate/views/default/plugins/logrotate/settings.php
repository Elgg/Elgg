<?php
/**
 * Elgg log rotator plugin settings.
 *
 * @package ElggLogRotate
 */

$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('logrotate:period'),
	'name' => 'params[period]',
	'options_values' => [
		'weekly' => elgg_echo('interval:weekly'),
		'monthly' => elgg_echo('interval:monthly'),
		'yearly' => elgg_echo('interval:yearly'),
	],
	'value' => $plugin->period,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('logrotate:delete'),
	'name' => 'params[delete]',
	'options_values' => [
		'weekly' => elgg_echo('logrotate:week'),
		'monthly' => elgg_echo('logrotate:month'),
		'yearly' => elgg_echo('logrotate:year'),
		'never' => elgg_echo('logrotate:never'),
	],
	'value' => $plugin->delete,
]);
