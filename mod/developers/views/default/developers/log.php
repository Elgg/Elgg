<?php
/**
 * Logging information
 */

$cache = elgg_get_config('log_cache');
$items = $cache->get();

// stop collecting messages
elgg_unregister_plugin_hook_handler('debug', 'log', [$cache, 'insertDump']);

$pres = array();
if ($items) {
	foreach ($items as $item) {
		$pres[] = '<pre>' . print_r($item, true) . '</pre>';
	}
}

// Add request info at top
$elapsed = microtime(true) - $GLOBALS['START_MICROTIME'];
$query_count = _elgg_services()->db->getQueryCount();
$boot_cache_rebuilt = !elgg_get_config('_boot_cache_hit');
$system_cache = (bool)elgg_get_config('system_cache_enabled');

$yes = elgg_echo('option:yes');
$no = elgg_echo('option:no');

$msgs[] = elgg_echo('developers:request_stats');
$msgs[] = elgg_echo('developers:elapsed_time') . ": " . sprintf('%1.3f', $elapsed);
$msgs[] = elgg_echo('developers:log_queries', [$query_count]);
if ($boot_cache_rebuilt) {
	$msgs[] = '(' . elgg_echo('developers:boot_cache_rebuilt') . ')';
}
$msgs[] = elgg_echo('developers:label:system_cache') . ": " . ($system_cache ? $yes : $no);

array_unshift($pres, "<pre>" . implode("\n", $msgs) . "</pre>");

echo '<div class="developers-log">' . implode('', $pres) . '</div>';
