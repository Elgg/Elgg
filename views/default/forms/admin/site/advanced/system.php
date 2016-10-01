<?php
/**
 * Advanced site settings, system section.
 */

$body = elgg_view_field([
	'#type' => 'text',
	'name' => 'wwwroot',
	'#label' => elgg_echo('installation:wwwroot'),
	'value' => elgg_get_config('wwwroot'),
]);

$body .= elgg_view_field([
	'#type' => 'text',
	'name' => 'path',
	'#label' => elgg_echo('installation:path'),
	'value' => elgg_get_config('path'),
]);

$dataroot_in_settings = !empty($GLOBALS['_ELGG']->dataroot_in_settings);
$body .= elgg_view_field([
	'#type' => 'text',
	'name' => 'dataroot',
	'#label' => elgg_echo('installation:dataroot'),
	'value' => elgg_get_config('dataroot'),
	'readonly' => $dataroot_in_settings,
	'class' => $dataroot_in_settings ? 'elgg-state-disabled' : '',
	'#help' => $dataroot_in_settings ? elgg_echo('admin:settings:in_settings_file') : '',
]);

echo elgg_view_module('inline', elgg_echo('admin:legend:system'), $body, ['id' => 'elgg-settings-advanced-system']);
