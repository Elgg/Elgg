<?php
/**
 * Advanced site settings, debugging section.
 */

$help = elgg_echo('installation:debug');
if (_elgg_config()->hasInitialValue('debug')) {
	$help .= '<br>' . elgg_echo('admin:settings:in_settings_file2');
}

$body = elgg_view_field([
	'#type' => 'select',
	'options_values' => [
		'' => elgg_echo('installation:debug:none'),
		'ERROR' => elgg_echo('installation:debug:error'),
		'WARNING' => elgg_echo('installation:debug:warning'),
		'NOTICE' => elgg_echo('installation:debug:notice'),
		'INFO' => elgg_echo('installation:debug:info'),
	],
	'name' => 'debug',
	'#label' => elgg_echo('installation:debug:label'),
	'#help' => $help,
	'value' => elgg_get_config('debug'),
	'disabled' => _elgg_config()->hasInitialValue('debug'),
]);

echo elgg_view_module('inline', elgg_echo('admin:legend:debug'), $body, ['id' => 'elgg-settings-advanced-debugging']);
