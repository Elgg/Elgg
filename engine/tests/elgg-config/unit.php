<?php
/**
 * settings.php for unit testing
 */
global $CONFIG;
$CONFIG = new stdClass();

$defaults = include __DIR__ . '/_defaults.php';

foreach ($defaults as $key => $value) {
	$CONFIG->$key = $value;
}

$CONFIG->db['split'] = true;
$CONFIG->db['read'][0]['dbhost'] = 0;
$CONFIG->db['read'][0]['dbuser'] = 'user0';
$CONFIG->db['read'][0]['dbpass'] = 'xxxx0';
$CONFIG->db['read'][0]['dbname'] = 'elgg0';
$CONFIG->db['read'][0]['dbname'] = 'elgg0';
$CONFIG->db['write'][0]['dbhost'] = 1;
$CONFIG->db['write'][0]['dbuser'] = 'user1';
$CONFIG->db['write'][0]['dbpass'] = 'xxxx1';
$CONFIG->db['write'][0]['dbname'] = 'elgg1';

$CONFIG->system_cache_enabled = false;
$CONFIG->simplecache_enabled = false;
$CONFIG->boot_cache_ttl = 0;