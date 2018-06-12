<?php
/**
 * Logging information
 */

// Add request info at top
$elapsed = microtime(true) - elgg_extract('START_MICROTIME', $GLOBALS);
$query_count = _elgg_services()->db->getQueryCount();
$boot_cache_rebuilt = !elgg_get_config('_boot_cache_hit');
$system_cache = elgg_is_system_cache_enabled();

$yes = elgg_echo('option:yes');
$no = elgg_echo('option:no');

$msgs = [];
$msgs[] = elgg_view_title(elgg_echo('developers:request_stats'));
$msgs[] = elgg_echo('developers:elapsed_time') . ": " . sprintf('%1.3f', $elapsed);
$msgs[] = elgg_echo('developers:log_queries', [$query_count]);
if ($boot_cache_rebuilt) {
	$msgs[] = '(' . elgg_echo('developers:boot_cache_rebuilt') . ')';
}
$msgs[] = elgg_echo('developers:label:system_cache') . ": " . ($system_cache ? $yes : $no);

$log = '';

$log_file = elgg()->config->log_cache;
if (is_file($log_file)) {
	$log = file_get_contents($log_file);
	unlink($log_file);
}
?>
<div class="developers-log">
	<pre>
		<?= implode("\r\n", $msgs) ?>
	</pre>
	<br />
	<?= nl2br($log) ?>
</div>
