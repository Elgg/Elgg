<?php
/**
 * settings.php for integration testing
 */
global $CONFIG;
$CONFIG = new stdClass();

$settings = \Elgg\Application::elggDir()->getPath('elgg-config/settings.php');
if (is_file($settings)) {
	include $settings;
}

$defaults = include __DIR__ . '/_overrides.php';

foreach ($defaults as $key => $value) {
	$CONFIG->$key = $value;
}

$CONFIG->memcache = true;
$CONFIG->memcache_servers = [
	[
		'127.0.0.1',
		11211
	],
];
$CONFIG->memcache_namespace_prefix = 'elgg_';
