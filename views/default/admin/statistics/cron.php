<?php
/**
 * Cron statistics
 */

//$cronhooks = $CONFIG->hooks["cron"];
$periods = elgg_get_config('elgg_cron_periods');
$table_content = '';
foreach ($periods as $period) {
	$name = elgg_echo("interval:$period");
	$key = "cron_latest:$period:ts";
	$ts = elgg_get_site_entity()->getPrivateSetting($key);
	if ($ts) {
		$friendly_time = elgg_view_friendly_time($ts);
		$date = date('r', $ts);
	} else {
		$friendly_time = elgg_echo('never');
		$date = '';
	}

	$msg_key = "cron_latest:$period:msg";
	$msg = elgg_get_site_entity()->getPrivateSetting($msg_key);
	if ($msg) {
		$msg = nl2br($msg);
	}
	$table_content .= "<tr><td>$name</td><td>$friendly_time</td><td>$date</td><td>$msg</td><tr>";
}

$period_hd = elgg_echo('admin:cron:period');
$friendly_hd = elgg_echo('admin:cron:friendly');
$date_hd = elgg_echo('admin:cron:date');
$msg_hd = elgg_echo('admin:cron:msg');

$table = <<<HTML
<table class="elgg-table">
	<tr><th>$period_hd</th><th>$friendly_hd</th><th>$date_hd</th><th>$msg_hd</th></tr>
	$table_content
</table>
HTML;

echo elgg_view_module('inline', elgg_echo('admin:cron:record'), $table);
