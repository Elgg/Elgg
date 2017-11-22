<?php

if (php_sapi_name() !== "cli") {
	die('CLI only');
}

if (!class_exists('Memcached')) {
	fwrite(STDOUT, 'PHP memcache module not installed');
	exit(1);
}

$memcached = new \Memcached;
$memcached->addServer('127.0.0.1', 11211);
$stats = $memcached->getStats();

if (!array_key_exists("127.0.0.1:11211", $stats)) {
	fwrite(STDOUT, 'Failed to connect to memcache server');
	exit(2);
}

fwrite(STDOUT, 'Successfully connected to memcache server');
exit(0);