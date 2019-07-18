<?php
/**
 * Show performance suggestions / warnings
 */

$tabs = [];

$tabs[] = [
	'text' => elgg_echo('admin:performance:label:generic'),
	'content' => elgg_view('admin/performance/generic'),
	'selected' => true,
];

// opcache
if (function_exists('opcache_get_status') && opcache_get_status(false)) {
	$tabs[] = [
		'text' => elgg_echo('admin:server:label:opcache'),
		'content' => elgg_view('admin/server/opcache'),
	];
}

// memcache
$memcache_servers = elgg_get_config('memcache_servers');
if (elgg_get_config('memcache') && !empty($memcache_servers) && \Stash\Driver\Memcache::isAvailable()) {
	$tabs[] = [
		'text' => elgg_echo('admin:server:label:memcache'),
		'content' => elgg_view('admin/server/memcache'),
	];
}

// redis
$redis_servers = elgg_get_config('redis_servers');
if (elgg_get_config('redis') && !empty($redis_servers) && \Stash\Driver\Redis::isAvailable()) {
	$tabs[] = [
		'text' => elgg_echo('admin:server:label:redis'),
		'content' => elgg_view('admin/server/redis'),
	];
}

echo elgg_view('page/components/tabs', [
	'tabs' => $tabs,
]);
