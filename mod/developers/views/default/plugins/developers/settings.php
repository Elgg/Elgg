<?php

elgg_import_esm('plugins/developers/settings');

/* @var $plugin \ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$config = elgg()->config;

echo elgg_view('output/longtext', [
	'value' => elgg_echo('elgg_dev_tools:settings:explanation'),
]);

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('developers:label:simple_cache'),
	'#help' => elgg_echo('developers:help:simple_cache'),
	'name' => 'simple_cache',
	'value' => _elgg_services()->simpleCache->isEnabled(),
	'disabled' => $config->hasInitialValue('simplecache_enabled'),
]);

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('developers:label:system_cache'),
	'#help' => elgg_echo('developers:help:system_cache'),
	'name' => 'system_cache',
	'value' => _elgg_services()->systemCache->isEnabled(),
]);

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('developers:label:display_errors'),
	'#help' => elgg_echo('developers:help:display_errors'),
	'name' => 'params[display_errors]',
	'value' => $plugin->display_errors,
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
		'' => elgg_echo('installation:debug:none'),
		\Psr\Log\LogLevel::ERROR => elgg_echo('installation:debug:error'),
		\Psr\Log\LogLevel::WARNING => elgg_echo('installation:debug:warning'),
		\Psr\Log\LogLevel::NOTICE => elgg_echo('installation:debug:notice'),
		\Psr\Log\LogLevel::INFO => elgg_echo('installation:debug:info'),
	],
]);

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('developers:label:screen_log'),
	'#help' => elgg_echo('developers:help:screen_log'),
	'name' => 'params[screen_log]',
	'value' => $plugin->screen_log,
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
	'#type' => 'switch',
	'#label' => elgg_echo('developers:label:wrap_views'),
	'#help' => elgg_echo('developers:help:wrap_views'),
	'name' => 'params[wrap_views]',
	'value' => $plugin->wrap_views,
]);

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('developers:label:log_events'),
	'#help' => elgg_echo('developers:help:log_events'),
	'name' => 'params[log_events]',
	'value' => $plugin->log_events,
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
