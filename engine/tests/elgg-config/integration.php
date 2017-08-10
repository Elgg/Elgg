<?php
/**
 * settings.php for integration testing
 */
global $CONFIG;
$CONFIG = new stdClass();

$settings = \Elgg\Application::elggDir()->getPath('elgg-config/settings.php');
include $settings;

$defaults = include __DIR__ . '/_defaults.php';

if (class_exists('Memcache')) {
	$memcached = new Memcache;
	if ($memcached->connect('127.0.0.1', 11211) && $memcached->close()) {
		// Memcached configuration for Travis
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

foreach ($defaults as $key => $value) {
	if (!isset($CONFIG->$key)) {
		$CONFIG->$key = $value;
	}
}
