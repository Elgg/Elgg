<?php
/**
 * Developer settings
 */

$data = array(
	'simple_cache' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_config('simplecache_enabled') == 1,
	),

	'system_cache' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_config('system_cache_enabled') == 1,
	),

	'display_errors' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('display_errors', 'developers') == 1,
	),

	'debug_level' => array(
		'type' => 'dropdown',
		'value' => elgg_get_config('debug'),
		'options_values' => array(
			false => elgg_echo('developers:debug:off'),
			'ERROR' => elgg_echo('developers:debug:error'),
			'WARNING' => elgg_echo('developers:debug:warning'),
			'NOTICE' => elgg_echo('developers:debug:notice'),
		),
	),

	'screen_log' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('screen_log', 'developers') == 1,
	),
	
	'show_strings' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('show_strings', 'developers') == 1,
	),

	'wrap_views' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('wrap_views', 'developers') == 1,
	),

	'log_events' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_plugin_setting('log_events', 'developers') == 1,
	),
);

$form_vars = array('id' => 'developer-settings-form', 'class' => 'elgg-form-settings');
$body_vars = array('data' => $data);
echo elgg_view_form('developers/settings', $form_vars, $body_vars);
