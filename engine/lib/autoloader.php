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
 * @param \Elgg\Di\ServiceProvider $services Elgg service provider. This must be set by the application.
 * @return \Elgg\Di\ServiceProvider
 * @access private
 */
function _elgg_services(\Elgg\Di\ServiceProvider $services = null) {
	static $inst;
	if ($services !== null) {
		$inst = $services;
	}
	return $inst;
}

/**
 * Delete the autoload system cache
 *
 * @access private
 */
function _elgg_delete_autoload_cache() {
	_elgg_services()->autoloadManager->deleteCache();
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('upgrade', 'all', '_elgg_delete_autoload_cache', 600);
};
