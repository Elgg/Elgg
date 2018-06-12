<?php

if (php_sapi_name() !== "cli") {
	die('CLI only');
}

if (!class_exists('Redis')) {
	fwrite(STDOUT, 'PHP redis extension not installed');
	exit(1);
}

try {
	$redis = new \Redis;
	$redis->connect('127.0.0.1', 6379);
	$stats = $redis->info();
} catch (Exception $ex) {
	fwrite(STDOUT, $ex->getMessage());
	exit(2);
}

fwrite(STDOUT, 'Successfully connected to redis server');
exit(0);