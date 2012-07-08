<?php
/**
 * Elgg autoloader
 * Facilities for class/interface/trait autoloading.
 *
 * @package    Elgg.Core
 * @subpackage Autoloader
 */

_elgg_setup_autoload();

/**
 * Setup global class map and loader instances and add the core classes to the map.
 * We can't load this from dataroot because we don't know it yet, and we'll need
 * several classes before we can find out!
 *
 * @throws InstallationException
 * @access private
 */
function _elgg_setup_autoload() {
	global $CONFIG;

	// manually load classes needed for autoloading
	$dir = dirname(dirname(__FILE__)) . '/classes';
	foreach (array('ElggClassMap', 'ElggClassLoader', 'ElggAutoloadManager') as $class) {
		$file = "{$dir}/{$class}.php";
		if (!include $file) {
			throw new InstallationException("Could not load {$file}");
		}
	}

	$loader = new ElggClassLoader(new ElggClassMap());
	// until the cache can be loaded, just setup PSR-0 autoloading
	// out of the classes directory. No need to build a full map.
	$loader->addFallback($dir);
	$loader->register();
	$manager = new ElggAutoloadManager($loader);

	$CONFIG->autoload_manager = $manager;
}

/**
 * Load cached data into the autoload system
 *
 * Note this has to wait until Elgg's data path is known.
 *
 * @access private
 */
function _elgg_load_autoload_cache() {
	$manager = _elgg_get_autoload_manager();
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
	_elgg_get_autoload_manager()->saveCache();
}

/**
 * Delete the autoload system cache
 *
 * @access private
 */
function _elgg_delete_autoload_cache() {
	_elgg_get_autoload_manager()->deleteCache();
}

/**
 * Get Elgg's autoload manager instance
 *
 * @return ElggAutoloadManager
 * @access private
 */
function _elgg_get_autoload_manager() {
	global $CONFIG;
	return $CONFIG->autoload_manager;
}

/**
 * Get Elgg's class loader
 *
 * @return ElggClassLoader
 */
function elgg_get_class_loader() {
	global $CONFIG;
	return $CONFIG->autoload_manager->getLoader();
}

/**
 * Recursively scan $dir and register the classes/interfaces/traits found
 * within for autoloading.
 *
 * ElggClassScanner is used, so the files do not need to follow any particular
 * naming/structure conventions, and the scan is only performed on the first
 * request.
 *
 * @param string $dir The dir to look in
 *
 * @return void
 * @since 1.8.0
 */
function elgg_register_classes($dir) {
	_elgg_get_autoload_manager()->addClasses($dir);
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
	_elgg_get_autoload_manager()->setClassPath($class, $location);
	return true;
}


elgg_register_event_handler('shutdown', 'system', '_elgg_save_autoload_cache', 1000);
elgg_register_event_handler('ugprade', 'all', '_elgg_delete_autoload_cache');
