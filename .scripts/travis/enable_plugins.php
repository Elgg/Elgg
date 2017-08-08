<?php

/**
 * Enables all core plugins
 */

$root = dirname(dirname(__DIR__));
require_once "$root/autoloader.php";

\Elgg\Application::start();

$plugins = _elgg_services()->plugins->find('inactive');
foreach ($plugins as $plugin) {
	$plugin->activate();
}