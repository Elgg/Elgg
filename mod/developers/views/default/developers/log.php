<?php
/**
 * Logging information
 */

$cache = elgg_get_config('log_cache');
$items = $cache->get();

$pres = array();
if ($items) {
	foreach ($items as $item) {
		$pres[] = '<pre>' . print_r($item, true) . '</pre>';
	}
}

// Add query count to top.
$msg = elgg_echo('developers:log_queries', array(_elgg_services()->db->getQueryCount()));
array_unshift($pres, "<pre>$msg</pre>");

echo '<div class="developers-log">' . implode('', $pres) . '</div>';
