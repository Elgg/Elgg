<?php
/**
 * The wire plugin settings
 */

$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('thewire:settings:limit'),
	'name' => 'params[limit]',
	'value' => (int) $plugin->limit,
	'id' => 'thewire-limit',
	'options_values' => [
		0 => elgg_echo('thewire:settings:limit:none'),
		140 => '140',
		250 => '250',
	],
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('thewire:settings:enable_editing'),
	'name' => 'params[enable_editing]',
	'checked' => (bool) $plugin->enable_editing,
	'switch' => true,
]);
