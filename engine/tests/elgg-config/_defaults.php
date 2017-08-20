<?php
/**
 * Testing config defaults
 */

$defaults = [
	'dbprefix' => 'elgg_t_i_',
	'boot_complete' => false,
	'wwwroot' => 'http://localhost/',
	'dataroot' => \Elgg\Project\Paths::elgg() . 'engine/tests/test_files/dataroot/',
	'cacheroot' => \Elgg\Project\Paths::elgg() . '/engine/tests/test_files/cacheroot/',
	'plugins_path' => \Elgg\Project\Paths::elgg() . '/mod/',
	'site_guid' => 1,
	'AutoloaderManager_skip_storage' => true,
	'simplecache_enabled' => false,
	'system_cache_enabled' => false,
	'boot_cache_ttl' => 0,
	'Elgg\Application_phpunit' => true,
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
			'square' => false,
			'upscale' => false
		],
		'master' => [
			'w' => 550,
			'h' => 550,
			'square' => false,
			'upscale' => false
		],
	],
	'debug' => 'NOTICE',
];

if (class_exists('Memcache')) {
	$memcached = new Memcache;
	if ($memcached->connect('127.0.0.1', 11211) && $memcached->close()) {
		$defaults['memcache'] = true;
		$defaults['memcache_servers'] = [
			[
				'127.0.0.1',
				11211
			],
		];
		$defaults['memcache_namespace_prefix'] = 'elgg_';
	}
}

return $defaults;