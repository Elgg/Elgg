<?php

global $CONFIG;
use Elgg\Project\Paths;

$CONFIG = new stdClass();

$settings = [
	'dbprefix' => getenv('ELGG_DB_PREFIX') ? : 't_i_elgg_',
	'dbname' => getenv('ELGG_DB_NAME') ? : '',
	'dbuser' => getenv('ELGG_DB_USER') ? : '',
	'dbpass' => getenv('ELGG_DB_PASS') ? : '',
	'dbhost' => getenv('ELGG_DB_HOST') ? : 'localhost',
	'dbencoding' => getenv('ELGG_DB_ENCODING') ? : 'utf8mb4',

	'memcache' => (bool) getenv('ELGG_MEMCACHE'),
	'memcache_servers' => [
		[
			getenv('ELGG_MEMCACHE_SERVER1_HOST'),
			getenv('ELGG_MEMCACHE_SERVER1_PORT')
		],
		[
			getenv('ELGG_MEMCACHE_SERVER2_HOST'),
			getenv('ELGG_MEMCACHE_SERVER2_PORT')
		],
	],
	'memcache_namespace_prefix' => getenv('ELGG_MEMCACHE_NAMESPACE_PREFIX') ? : 'elgg_mc_prefix_',

	'redis' => (bool) getenv('ELGG_REDIS'),
	'redis_servers' => [
		[
			getenv('ELGG_REDIS_SERVER1_HOST'),
			getenv('ELGG_REDIS_SERVER1_PORT')
		],
	],

	// These are fixed, because tests rely on specific location of the dataroot for source files
	'wwwroot' => getenv('ELGG_WWWROOT') ? : 'http://localhost/',
	'dataroot' => Paths::elgg() . 'engine/tests/test_files/dataroot/',
	'cacheroot' => Paths::elgg() . 'engine/tests/test_files/cacheroot/',
	'assetroot' => Paths::elgg() . 'engine/tests/test_files/assetroot/',
	'plugins_path' => Paths::elgg() . 'mod/',

	'system_cache_enabled' => false,
	'simplecache_enabled' => false,
	'boot_cache_ttl' => 0,

	'icon_sizes' => [
		'topbar' => [
			'w' => 16,
			'h' => 16,
			'square' => true,
			'upscale' => true
		],
		'tiny' => [
			'w' => 25,
			'h' => 25,
			'square' => true,
			'upscale' => true
		],
		'small' => [
			'w' => 40,
			'h' => 40,
			'square' => true,
			'upscale' => true
		],
		'medium' => [
			'w' => 100,
			'h' => 100,
			'square' => true,
			'upscale' => true
		],
		'large' => [
			'w' => 200,
			'h' => 200,
			'square' => true,
			'upscale' => true
		],
		'master' => [
			'w' => 10240,
			'h' => 10240,
			'square' => false,
			'upscale' => false
		],
	],
	'debug' => 'NOTICE',
];

foreach ($settings as $key => $value) {
	$CONFIG->$key = $value;
}