<?php
/**
 * Enables all core plugins
 */

require_once dirname(dirname(__DIR__)) . '/autoloader.php';

\Elgg\Application::start();

$plugins = _elgg_services()->plugins->find('inactive');
foreach ($plugins as $plugin) {
	$plugin->activate();
}
