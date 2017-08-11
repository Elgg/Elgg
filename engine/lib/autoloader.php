<?php
/**
 * Elgg autoloader
 * Facilities for class/interface/trait autoloading.
 *
 * @package    Elgg.Core
 * @subpackage Autoloader
 */

/**
 * Get the global service provider
 *
 * @return \Elgg\Di\ServiceProvider
 * @access private
 */
function _elgg_services() {
	return elgg()->_services;
}

/**
 * Get the Elgg config service
 *
 * @return \Elgg\Config
 * @access private
 */
function _elgg_config() {
	return _elgg_services()->config;
}

/**
 * Delete the autoload system cache
 *
 * @access private
 */
function _elgg_delete_autoload_cache() {
	_elgg_services()->autoloadManager->deleteCache();
}


/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('upgrade', 'all', '_elgg_delete_autoload_cache', 600);
};
