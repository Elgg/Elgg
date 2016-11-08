<?php

if (php_sapi_name() !== "cli") {
	die('CLI only');
}

if (!class_exists('Memcache')) {
	fwrite(STDOUT, 'PHP memcache module not installed');
	exit(1);
}

$memcached = new Memcache;
if (!$memcached->connect('127.0.0.1', 11211)) {
	fwrite(STDOUT, 'Failed to connect to memcache server');
	exit(2);
}

$memcached->close();
fwrite(STDOUT, 'Successfully connected to memcache server');
exit(0);