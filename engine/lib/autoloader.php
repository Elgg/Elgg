<?php
/**
 * Elgg autoloader
 * Facilities for class/interface/trait autoloading.
 *
 * @package    Elgg.Core
 * @subpackage Autoloader
 */

/**
 * @return Elgg_Di_ServiceProvider
 * @access private
 */
function _elgg_services() {
	static $provider;
	if (null === $provider) {
		$provider = _elgg_create_service_provider();
	}
	return $provider;
}

/**
 * Sets up autoloading and creates the service provider (DIC)
 *
 * Setup global class map and loader instances and add the core classes to the map.
 * We can't load this from dataroot because we don't know it yet, and we'll need
 * several classes before we can find out!
 *
 * @throws RuntimeException
 * @access private
 */
function _elgg_create_service_provider() {
	// manually load classes needed for autoloading
	$dir = dirname(dirname(__FILE__)) . '/classes';
	foreach (array('Elgg_ClassMap', 'Elgg_ClassLoader', 'Elgg_AutoloadManager') as $class) {
		if (!class_exists($class)) {
			$file = "{$dir}/" . strtr($class, '_\\', '//') . ".php";
			include $file;
			if (!class_exists($class, false)) {
				throw new RuntimeException("Could not load {$class} in {$file}.");
			}
		}
	}

	$loader = new Elgg_ClassLoader(new Elgg_ClassMap());
	// until the cache can be loaded, just setup PSR-0 autoloading
	// out of the classes directory. No need to build a full map.
	$loader->addFallback($dir);
	$loader->register();
	$manager = new Elgg_AutoloadManager($loader);

	return new Elgg_Di_ServiceProvider($manager);
}

/**
 * Load cached data into the autoload system
 *
 * Note this has to wait until Elgg's data path is known.
 *
 * @access private
 */
function _elgg_load_autoload_cache() {
	$manager = _elgg_services()->autoloadManager;
	$manager->setStorage(elgg_get_system_cache());
	if (! $manager->loadCache()) {
		$manager->addClasses(dirname(dirname(__FILE__)) . '/classes');
	}
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
 * Get Elgg's class loader
 *
 * @return Elgg_ClassLoader
 */
function elgg_get_class_loader() {
	return _elgg_services()->autoloadManager->getLoader();
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

// set up autoloading and DIC
_elgg_services();

_elgg_services()->events->registerHandler('shutdown', 'system', '_elgg_save_autoload_cache', 1000);
_elgg_services()->events->registerHandler('upgrade', 'all', '_elgg_delete_autoload_cache');
