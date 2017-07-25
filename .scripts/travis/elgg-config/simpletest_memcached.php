<?php

global $CONFIG;

if (!isset($CONFIG)) {
	$CONFIG = new \stdClass;
}

$CONFIG->debug = 'NOTICE';

// Memcached configuration for Travis
$CONFIG->memcache = true;
$CONFIG->memcache_servers = [
	['127.0.0.1', 11211],
];
$CONFIG->memcache_namespace_prefix = 'elgg_';


