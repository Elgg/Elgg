<?php
/**
 * Developer settings
 */

$data = array(
	'simple_cache' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_config('simplecache_enabled') == 1,
		'readonly' => elgg_get_config('simplecache_enabled_in_settings'),
	),

	'system_cache' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_config('system_cache_enabled') == 1,
		'readonly' => false,
	),

	'display_errors' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('display_errors', 'developers') == 1,
		'readonly' => false,
	),

	'debug_level' => array(
		'type' => 'dropdown',
		'value' => elgg_get_config('debug'),
		'options_values' => array(
			false => elgg_echo('developers:debug:off'),
			'ERROR' => elgg_echo('developers:debug:error'),
			'WARNING' => elgg_echo('developers:debug:warning'),
			'NOTICE' => elgg_echo('developers:debug:notice'),
			'INFO' => elgg_echo('developers:debug:info'),
		),
		'readonly' => false,
	),

	'screen_log' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('screen_log', 'developers') == 1,
		'readonly' => false,
	),

	'show_strings' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('show_strings', 'developers') == 1,
		'readonly' => false,
	),

	'wrap_views' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('wrap_views', 'developers') == 1,
		'readonly' => false,
	),

	'log_events' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('log_events', 'developers') == 1,
		'readonly' => false,
	),
);

$form_vars = array('id' => 'developer-settings-form', 'class' => 'elgg-form-settings');
$body_vars = array('data' => $data);
echo elgg_view_form('developers/settings', $form_vars, $body_vars);
