<?php
/**
 * Developer settings
 */

$config = _elgg_config();
$debug_value = $config->hasInitialValue('debug') ? $config->getInitialValue('debug') : $config->debug;

$debug_help = elgg_echo('developers:help:debug_level');
if ($config->hasInitialValue('debug')) {
	$debug_help .= '<br>' . elgg_echo('admin:settings:in_settings_file');
}

$data = [
	'simple_cache' => [
		'#type' => 'checkbox',
		'value' => 1,
		'checked' => _elgg_config()->simplecache_enabled == 1,
		'disabled' => _elgg_config()->hasInitialValue('simplecache_enabled'),
		'switch' => true,
	],

	'system_cache' => [
		'#type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_is_system_cache_enabled(),
		'switch' => true,
	],

	'display_errors' => [
		'#type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('display_errors', 'developers') == 1,
		'switch' => true,
	],

	'debug_level' => [
		'#type' => 'select',
		'#help' => $debug_help,
		'value' => $debug_value,
		'disabled' => $config->hasInitialValue('debug'),
		'options_values' => [
			'' => elgg_echo('developers:debug:off'),
			'ERROR' => elgg_echo('developers:debug:error'),
			'WARNING' => elgg_echo('developers:debug:warning'),
			'NOTICE' => elgg_echo('developers:debug:notice'),
			'INFO' => elgg_echo('developers:debug:info'),
		],
	],

	'screen_log' => [
		'#type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('screen_log', 'developers') == 1,
		'switch' => true,
	],
	
	'show_strings' => [
		'#type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('show_strings', 'developers') == 1,
		'switch' => true,
	],

	'show_modules' => [
		'#type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('show_modules', 'developers') == 1,
		'switch' => true,
	],

	'wrap_views' => [
		'#type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('wrap_views', 'developers') == 1,
		'switch' => true,
	],

	'log_events' => [
		'#type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('log_events', 'developers') == 1,
		'switch' => true,
	],

	'show_gear' => [
		'#type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('show_gear', 'developers') == 1,
		'switch' => true,
	],
	
	'block_email' => [
		'#type' => 'select',
		'value' => elgg_get_plugin_setting('block_email', 'developers'),
		'options_values' => [
			'' => elgg_echo('option:no'),
			'forward' => elgg_echo('developers:block_email:forward'),
			'users' => elgg_echo('developers:block_email:users'),
			'all' => elgg_echo('developers:block_email:all'),
		],
	],
	
	'forward_email' => [
		'#type' => 'email',
		'#class' => elgg_get_plugin_setting('block_email', 'developers') === 'forward' ? '' : 'hidden',
		'value' => elgg_get_plugin_setting('forward_email', 'developers'),
	],

	'enable_error_log' => [
		'#type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('enable_error_log', 'developers') == 1,
		'switch' => true,
	],
];

$form_vars = [
	'id' => 'developer-settings-form',
	'class' => 'elgg-form-settings',
];
$body_vars = [
	'data' => $data,
];

echo elgg_view_form('developers/settings', $form_vars, $body_vars);
