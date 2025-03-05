<?php
/**
 * Elgg garbage collector plugin settings.
 */

$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('garbagecollector:period'),
	'name' => 'params[period]',
	'options_values' => [
		'never' => elgg_echo('never'),
		'weekly' => elgg_echo('garbagecollector:weekly'),
		'monthly' => elgg_echo('garbagecollector:monthly'),
		'yearly' => elgg_echo('garbagecollector:yearly'),
	],
	'value' => $plugin->period,
]);

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('garbagecollector:period:optimize'),
	'name' => 'params[optimize]',
	'value' => $plugin->optimize,
]);
