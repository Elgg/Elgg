<?php
/**
 * Elgg log rotator plugin settings.
 */

$plugin = elgg_extract('entity', $vars);
if (!$plugin instanceof ElggPlugin) {
	return;
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('logrotate:period'),
	'name' => 'params[period]',
	'options_values' => [
		'weekly' => elgg_echo('interval:weekly'),
		'monthly' => elgg_echo('interval:monthly'),
		'yearly' => elgg_echo('interval:yearly'),
		'never' => elgg_echo('never'),
	],
	'value' => $plugin->period,
]);

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('logrotate:retention'),
	'#help' => elgg_echo('logrotate:retention:help'),
	'name' => 'params[retention]',
	'value' => $plugin->retention,
	'min' => 0,
]);
