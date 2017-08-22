<?php
/**
 * settings.php
 */
global $CONFIG;
$CONFIG = new stdClass();

$defaults = include __DIR__ . '/_defaults.php';

foreach ($defaults as $key => $value) {
	$CONFIG->$key = $value;
}

$CONFIG->system_cache_enabled = false;
$CONFIG->simplecache_enabled = false;
$CONFIG->boot_cache_ttl = 0;
