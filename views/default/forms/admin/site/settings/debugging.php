<?php
/**
 * Advanced site settings, debugging section.
 */

$config = elgg()->config;
$value = $config->hasInitialValue('debug') ? $config->getInitialValue('debug') : $config->debug;

$help = elgg_echo('installation:debug');
if ($config->hasInitialValue('debug')) {
	$help .= '<br>' . elgg_echo('admin:settings:in_settings_file');
}

$body = elgg_view_field([
	'#type' => 'select',
	'options_values' => [
		'' => elgg_echo('installation:debug:none'),
		\Psr\Log\LogLevel::ERROR => elgg_echo('installation:debug:error'),
		\Psr\Log\LogLevel::WARNING => elgg_echo('installation:debug:warning'),
		\Psr\Log\LogLevel::NOTICE => elgg_echo('installation:debug:notice'),
		\Psr\Log\LogLevel::INFO => elgg_echo('installation:debug:info'),
	],
	'name' => 'debug',
	'#label' => elgg_echo('installation:debug:label'),
	'#help' => $help,
	'value' => $value,
	'disabled' => $config->hasInitialValue('debug'),
]);

echo elgg_view_module('info', elgg_echo('admin:legend:debug'), $body, ['id' => 'elgg-settings-advanced-debugging']);
