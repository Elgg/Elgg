<?php
/**
 * Elgg autoloader
 * Facilities for class/interface/trait autoloading.
 *
 * @package    Elgg.Core
 * @subpackage Autoloader
 */
use DI\ContainerBuilder;
use Doctrine\Common\Cache\ArrayCache;
use Elgg\Di\ServiceProvider;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';


global $ELGG_SERVICE_PROVIDER;

/**
 * @return ServiceProvider
 * @access private
 */
function _elgg_services() {
	global $ELGG_SERVICE_PROVIDER;
	if (!isset($ELGG_SERVICE_PROVIDER)) {
		$ELGG_SERVICE_PROVIDER = _elgg_create_service_provider();
	}
	return $ELGG_SERVICE_PROVIDER;
}


/**
 * Null out the current service provider. Useful for testing to get fresh instances.
 * 
 * @return void
 */
function _elgg_reset_services() {
	global $ELGG_SERVICE_PROVIDER;
	$ELGG_SERVICE_PROVIDER = null;
}


/**
 * Creates the service provider (DIC)
 *
 * @throws RuntimeException
 * 
 * @return ServiceProvider
 * @access private
 */
function _elgg_create_service_provider() {
	$builder = new ContainerBuilder();
	$builder->setDefinitionCache(new ArrayCache());
	$builder->addDefinitions(dirname(__DIR__) . "/di.php");
	return new ServiceProvider($builder->build());
}

/**
 * Save the autoload system cache
 *
 * @access private
 */
function _elgg_save_autoload_cache() {
	_elgg_services()->autoloadManager->saveCache();
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
 * Register a directory tree for autoloading classes/interfaces/traits.
 *
 * For BC with 1.8, all .php files in the top-level directory are scanned
 * and added to the class map (only on the first request), then lower-level
 * directories are registered for standard PSR-0 autoloading.
 *
 * @param string $dir The dir to look in
 *
 * @return void
 * @since 1.8.0
 */
function elgg_register_classes($dir) {
	_elgg_services()->autoloadManager->addClasses($dir);
}

/**
 * Register a classname to a file.
 *
 * @param string $class    The name of the class
 * @param string $location The location of the file
 *
 * @return bool true
 * @since 1.8.0
 */
function elgg_register_class($class, $location) {
	_elgg_services()->autoloadManager->setClassPath($class, $location);
	return true;
}

_elgg_services()->events->registerHandler('shutdown', 'system', '_elgg_save_autoload_cache', 1000);
_elgg_services()->events->registerHandler('upgrade', 'all', '_elgg_delete_autoload_cache');
