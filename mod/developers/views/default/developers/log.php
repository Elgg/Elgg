<?php
/**
 * Logging information
 */

$elapsed = microtime(true) - elgg_extract('START_MICROTIME', $GLOBALS);

$log = elgg_format_element('div', [], elgg_echo('developers:elapsed_time') . ': <b>' . sprintf('%1.3f', $elapsed) . '</b>');
$log .= elgg_format_element('div', [], elgg_echo('developers:log_queries', ['<b>' . _elgg_services()->db->getQueryCount() . '</b>']));

if (!elgg_get_config('_boot_cache_hit')) {
	$log .= elgg_format_element('div', [], '<b>(' . elgg_echo('developers:boot_cache_rebuilt') . ')</b>');
}

$system_log_enabled = _elgg_services()->systemCache->isEnabled() ? elgg_echo('option:yes') : elgg_echo('option:no');
$log .= elgg_format_element('div', [], elgg_echo('developers:label:system_cache') . ": <b>{$system_log_enabled}</b>");

$log_file = elgg()->config->log_cache;
if (is_file($log_file)) {
	$log .= '<br />' . file_get_contents($log_file);
	unlink($log_file);
}

echo elgg_view_module('default', elgg_echo('developers:request_stats'), $log, ['class' => 'developers-log']);
