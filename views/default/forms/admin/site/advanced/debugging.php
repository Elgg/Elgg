<?php
/**
 * Advanced site settings, debugging section.
 */

$body = elgg_view_field([
	'#type' => 'select',
	'options_values' => [
		'0' => elgg_echo('installation:debug:none'),
		'ERROR' => elgg_echo('installation:debug:error'),
		'WARNING' => elgg_echo('installation:debug:warning'),
		'NOTICE' => elgg_echo('installation:debug:notice'),
		'INFO' => elgg_echo('installation:debug:info'),
	],
	'name' => 'debug',
	'#label' => elgg_echo('installation:debug:label'),
	'#help' => elgg_echo('installation:debug'),
	'value' => elgg_get_config('debug'),
]);

echo elgg_view_module('inline', elgg_echo('admin:legend:debug'), $body, ['id' => 'elgg-settings-advanced-debugging']);
