<?php
/**
 * Testing config defaults
 */

return [
	'dbprefix' => 'elgg_t_i_',
	'boot_complete' => false,
	'wwwroot' => 'http://localhost/',
	'dataroot' => \Elgg\Application::elggDir()->getPath('/engine/tests/phpunit/test_files/dataroot/'),
	'cacheroot' => \Elgg\Application::elggDir()->getPath('/engine/tests/phpunit/test_files/cacheroot/'),
	'site_guid' => 1,
	'AutoloaderManager_skip_storage' => true,
	'simplecache_enabled' => false,
	'system_cache_enabled' => false,
	'Elgg\Application_phpunit' => true,
	'icon_sizes' => array(
		'topbar' => array('w' => 16, 'h' => 16, 'square' => true, 'upscale' => true),
		'tiny' => array('w' => 25, 'h' => 25, 'square' => true, 'upscale' => true),
		'small' => array('w' => 40, 'h' => 40, 'square' => true, 'upscale' => true),
		'medium' => array('w' => 100, 'h' => 100, 'square' => true, 'upscale' => true),
		'large' => array('w' => 200, 'h' => 200, 'square' => false, 'upscale' => false),
		'master' => array('w' => 550, 'h' => 550, 'square' => false, 'upscale' => false),
	),
	'debug' => 'NOTICE',
];