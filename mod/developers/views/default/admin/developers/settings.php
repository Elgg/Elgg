<?php
/**
 * Developer settings
 */

$data = [
	'simple_cache' => [
		'type' => 'checkbox',
		'value' => 1,
		'checked' => _elgg_config()->simplecache_enabled == 1,
		'disabled' => _elgg_config()->hasInitialValue('simplecache_enabled'),
	],

	'system_cache' => [
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_is_system_cache_enabled(),
		'readonly' => false,
		'disabled' => false,
	],

	'display_errors' => [
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('display_errors', 'developers') == 1,
		'readonly' => false,
		'disabled' => false,
	],

	'debug_level' => [
		'type' => 'dropdown',
		'value' => _elgg_config()->debug,
		'options_values' => [
			'' => elgg_echo('developers:debug:off'),
			'ERROR' => elgg_echo('developers:debug:error'),
			'WARNING' => elgg_echo('developers:debug:warning'),
			'NOTICE' => elgg_echo('developers:debug:notice'),
			'INFO' => elgg_echo('developers:debug:info'),
		],
		'readonly' => false,
		'disabled' => _elgg_config()->hasInitialValue('debug'),
	],

	'screen_log' => [
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('screen_log', 'developers') == 1,
		'readonly' => false,
		'disabled' => false,
	],
	
	'show_strings' => [
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('show_strings', 'developers') == 1,
		'readonly' => false,
		'disabled' => false,
	],

	'show_modules' => [
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('show_modules', 'developers') == 1,
		'readonly' => false,
		'disabled' => false,
	],

	'wrap_views' => [
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('wrap_views', 'developers') == 1,
		'readonly' => false,
		'disabled' => false,
	],

	'log_events' => [
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('log_events', 'developers') == 1,
		'readonly' => false,
		'disabled' => false,
	],

	'show_gear' => [
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('show_gear', 'developers') == 1,
		'readonly' => false,
		'disabled' => false,
	],
];

$form_vars = ['id' => 'developer-settings-form', 'class' => 'elgg-form-settings'];
$body_vars = ['data' => $data];
echo elgg_view_form('developers/settings', $form_vars, $body_vars);
