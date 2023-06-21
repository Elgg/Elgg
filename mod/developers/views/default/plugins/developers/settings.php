<?php

elgg_require_js('plugins/developers/settings');

/* @var $plugin \ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$config = elgg()->config;

echo elgg_view('output/longtext', [
	'value' => elgg_echo('elgg_dev_tools:settings:explanation'),
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('developers:label:simple_cache'),
	'#help' => elgg_echo('developers:help:simple_cache'),
	'name' => 'simple_cache',
	'value' => 1,
	'checked' => elgg_is_simplecache_enabled(),
	'disabled' => $config->hasInitialValue('simplecache_enabled'),
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('developers:label:system_cache'),
	'#help' => elgg_echo('developers:help:system_cache'),
	'name' => 'system_cache',
	'value' => 1,
	'checked' => elgg_is_system_cache_enabled(),
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('developers:label:display_errors'),
	'#help' => elgg_echo('developers:help:display_errors'),
	'name' => 'params[display_errors]',
	'value' => 1,
	'checked' => $plugin->display_errors === '1',
	'switch' => true,
]);

$debug_value = $config->hasInitialValue('debug') ? $config->getInitialValue('debug') : $config->debug;

$debug_help = elgg_echo('developers:help:debug_level');
if ($config->hasInitialValue('debug')) {
	$debug_help .= '<br>' . elgg_echo('admin:settings:in_settings_file');
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('developers:label:debug_level'),
	'#help' => $debug_help,
	'name' => 'debug_level',
	'value' => $debug_value,
	'disabled' => $config->hasInitialValue('debug'),
	'options_values' => [
		'' => elgg_echo('developers:debug:off'),
		'ERROR' => elgg_echo('developers:debug:error'),
		'WARNING' => elgg_echo('developers:debug:warning'),
		'NOTICE' => elgg_echo('developers:debug:notice'),
		'INFO' => elgg_echo('developers:debug:info'),
	],
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('developers:label:screen_log'),
	'#help' => elgg_echo('developers:help:screen_log'),
	'name' => 'params[screen_log]',
	'value' => 1,
	'checked' => $plugin->screen_log === '1',
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('developers:label:show_strings'),
	'#help' => elgg_echo('developers:help:show_strings'),
	'name' => 'params[show_strings]',
	'options_values' => [
		0 => elgg_echo('developers:show_strings:default'),
		1 => elgg_echo('developers:show_strings:key_append'),
		2 => elgg_echo('developers:show_strings:key_only'),
	],
	'value' => $plugin->show_strings,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('developers:label:show_modules'),
	'#help' => elgg_echo('developers:help:show_modules'),
	'name' => 'params[show_modules]',
	'value' => 1,
	'checked' => $plugin->show_modules === '1',
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('developers:label:wrap_views'),
	'#help' => elgg_echo('developers:help:wrap_views'),
	'name' => 'params[wrap_views]',
	'value' => 1,
	'checked' => $plugin->wrap_views === '1',
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('developers:label:log_events'),
	'#help' => elgg_echo('developers:help:log_events'),
	'name' => 'params[log_events]',
	'value' => 1,
	'checked' => $plugin->log_events === '1',
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('developers:label:block_email'),
	'#help' => elgg_echo('developers:help:block_email'),
	'name' => 'params[block_email]',
	'value' => $plugin->block_email,
	'options_values' => [
		'' => elgg_echo('option:no'),
		'forward' => elgg_echo('developers:block_email:forward'),
		'users' => elgg_echo('developers:block_email:users'),
		'all' => elgg_echo('developers:block_email:all'),
	],
]);

echo elgg_view_field([
	'#type' => 'email',
	'#label' => elgg_echo('developers:label:forward_email'),
	'#help' => elgg_echo('developers:help:forward_email'),
	'#class' => $plugin->block_email === 'forward' ? '' : 'hidden',
	'name' => 'params[forward_email]',
	'value' => $plugin->forward_email,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('developers:label:enable_error_log'),
	'#help' => elgg_echo('developers:help:enable_error_log'),
	'name' => 'params[enable_error_log]',
	'value' => 1,
	'checked' => $plugin->enable_error_log === '1',
	'switch' => true,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'flush_cache',
	'value' => 1,
]);
