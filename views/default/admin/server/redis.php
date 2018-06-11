<?php
/**
 * Memcache info
 */
$servers = elgg_get_config('redis_servers');
if (!elgg_get_config('redis') || empty($servers) || !\Stash\Driver\Redis::isAvailable()) {
	echo '<p>' . elgg_echo('admin:server:redis:inactive') . '</p>';

	return;
}

$redis = new Redis();

foreach ($servers as $server) {
	$redis->connect($server[0], $server[1]);
}

$stats = $redis->info();

$rows = [];

foreach ($stats as $key => $value) {
	$row = [];
	$row[] = elgg_format_element('td', [], elgg_format_element('b', [], $key . ':'));
	$row[] = elgg_format_element('td', [], $value);

	$rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $row));
}

echo elgg_format_element('table', ['class' => 'elgg-table-alt'], implode(PHP_EOL, $rows));
