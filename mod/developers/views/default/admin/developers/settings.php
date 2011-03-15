<?php
/**
 * Developer settings
 */

$sections = array(
	'simple_cache' => 'checkbox',
	'view_path_cache' => 'checkbox',
	'display_errors' => 'checkbox',
	'debug_level' => 'pulldown',
);

$data = array(
	'simple_cache' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_config('simplecache_enabled') == 1,
	),

	'view_path_cache' => array(
		'type' => 'checkbox',
		'value' => 1,
		'checked' => elgg_get_config('viewpath_cache_enabled') == 1,
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
);

$form_vars = array('id' => 'developer-settings-form');
$body_vars = array('data' => $data);
echo elgg_view_form('developers/settings', $form_vars, $body_vars);
