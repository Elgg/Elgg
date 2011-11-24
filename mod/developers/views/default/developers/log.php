<?php
/**
 * Logging information
 */

$cache = elgg_get_config('log_cache');
$items = $cache->get();

echo '<div class="developers-log">';
if ($items) {
	foreach ($items as $item) {
		echo '<pre>';
		print_r($item);
		echo '</pre>';
	}
}

echo '</div>';