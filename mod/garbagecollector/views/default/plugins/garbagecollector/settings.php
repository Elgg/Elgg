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
		'weekly' => elgg_echo('garbagecollector:weekly'),
		'monthly' => elgg_echo('garbagecollector:monthly'),
		'yearly' => elgg_echo('garbagecollector:yearly'),
	],
	'value' => $plugin->period,
]);
