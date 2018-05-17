<?php
/**
 * Memcache info
 */
$servers = elgg_get_config('memcache_servers');
if (!elgg_get_config('memcache') || empty($servers) || !\Stash\Driver\Memcache::isAvailable()) {
	echo '<p>' . elgg_echo('admin:server:memcache:inactive') . '</p>';
	return;
}

if (class_exists('Memcached')) {
	$memcache = new Memcached();
} else {
	$memcache = new Memcache();
}

foreach ($servers as $server) {
	$memcache->addserver($server[0], $server[1]);
}

if ($memcache instanceof Memcache) {
	$stats = $memcache->getextendedstats();
} else {
	$stats = $memcache->getStats();
}

foreach ($stats as $server => $server_stats) {
	if (empty($server_stats)) {
		// memcache server not available
		echo elgg_view_module('info', $server, elgg_echo('notfound'));
		continue;
	}
	
	$rows = [];
	
	foreach ($server_stats as $key => $value) {
		$row = [];
		$row[] = elgg_format_element('td', [], elgg_format_element('b', [], $key . ':'));
		$row[] = elgg_format_element('td', [], $value);
		
		$rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $row));
	}
	
	$table = elgg_format_element('table', ['class' => 'elgg-table-alt'], implode(PHP_EOL, $rows));
	
	echo elgg_view_module('info', $server, $table);
}
