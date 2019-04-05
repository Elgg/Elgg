<?php
/**
 * Elgg cache
 * Cache file interface for caching data.
 *
 * @package    Elgg.Core
 * @subpackage Cache
 */

/**
 * Returns an \ElggCache object suitable for caching system information
 *
 * @return ElggCache
 */
function elgg_get_system_cache() {
	return _elgg_services()->fileCache;
}

/**
 * Reset the system cache by deleting the caches
 *
 * @return void
 */
function elgg_reset_system_cache() {
	_elgg_services()->systemCache->reset();
}

/**
 * Saves a system cache.
 *
 * @param string $type The type or identifier of the cache
 * @param string $data The data to be saved
 * @return bool
 */
function elgg_save_system_cache($type, $data) {
	return _elgg_services()->systemCache->save($type, $data);
}

/**
 * Retrieve the contents of a system cache.
 *
 * @param string $type The type of cache to load
 * @return string
 */
function elgg_load_system_cache($type) {
	return _elgg_services()->systemCache->load($type);
}

/**
 * Deletes the contents of a system cache.
 *
 * @param string $type The type of cache to delete
 * @return bool
 * @since 3.0
 */
function elgg_delete_system_cache($type) {
	return _elgg_services()->systemCache->delete($type);
}

/**
 * Is system cache enabled
 *
 * @return bool
 * @since 2.2.0
 */
function elgg_is_system_cache_enabled() {
	return _elgg_services()->systemCache->isEnabled();
}

/**
 * Enables the system disk cache.
 *
 * Uses the 'system_cache_enabled' config with a boolean value.
 * Resets the system cache.
 *
 * @return void
 */
function elgg_enable_system_cache() {
	_elgg_services()->systemCache->enable();
}

/**
 * Disables the system disk cache.
 *
 * Uses the 'system_cache_enabled' config with a boolean value.
 * Resets the system cache.
 *
 * @return void
 */
function elgg_disable_system_cache() {
	_elgg_services()->systemCache->disable();
}

/* Simplecache */

/**
 * Registers a view to simple cache.
 *
 * Simple cache is a caching mechanism that saves the output of
 * a view and its extensions into a file.
 *
 * @warning Simple cached views must take no parameters and return
 * the same content no matter who is logged in.
 *
 * @param string $view_name View name
 *
 * @return void
 * @see elgg_get_simplecache_url()
 * @since 1.8.0
 */
function elgg_register_simplecache_view($view_name) {
	_elgg_services()->views->registerCacheableView($view_name);
}

/**
 * Get the URL for the cached view.
 *
 * Recommended usage is to just pass the entire view name as the first and only arg:
 *
 * ```
 * $blog_js = elgg_get_simplecache_url('elgg/blog/save_draft.js');
 * $favicon = elgg_get_simplecache_url('favicon.ico');
 * ```
 *
 * For backwards compatibility with older versions of Elgg, this function supports
 * "js" or "css" as the first arg, with the rest of the view name as the second arg:
 *
 * ```
 * $blog_js = elgg_get_simplecache_url('js', 'elgg/blog/save_draft.js');
 * ```
 *
 * This automatically registers the view with Elgg's simplecache.
 *
 * @param string $view    The full view name
 * @param string $subview If the first arg is "css" or "js", the rest of the view name
 * @return string
 * @since 1.8.0
 */
function elgg_get_simplecache_url($view, $subview = '') {
	return _elgg_services()->simpleCache->getUrl($view, $subview);
}

/**
 * Is simple cache enabled
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_is_simplecache_enabled() {
	return _elgg_services()->simpleCache->isEnabled();
}

/**
 * Enables the simple cache.
 *
 * @see elgg_register_simplecache_view()
 * @return void
 * @since 1.8.0
 */
function elgg_enable_simplecache() {
	_elgg_services()->simpleCache->enable();
}

/**
 * Disables the simple cache.
 *
 * @warning Simplecache is also purged when disabled.
 *
 * @see elgg_register_simplecache_view()
 * @return void
 * @since 1.8.0
 */
function elgg_disable_simplecache() {
	_elgg_services()->simpleCache->disable();
}

/**
 * Recursively deletes a directory, including all hidden files.
 *
 * TODO(ewinslow): Move to filesystem package
 *
 * @param string $dir   The directory
 * @param bool   $empty If true, we just empty the directory
 *
 * @return boolean Whether the dir was successfully deleted.
 * @access private
 */
function _elgg_rmdir($dir, $empty = false) {
	if (!$dir) {
		// realpath can return false
		_elgg_services()->logger->warning(__FUNCTION__ . ' called with empty $dir');
		return true;
	}
	if (!is_dir($dir)) {
		return true;
	}

	$files = array_diff(scandir($dir), ['.', '..']);
	
	foreach ($files as $file) {
		if (is_dir("$dir/$file")) {
			_elgg_rmdir("$dir/$file");
		} else {
			unlink("$dir/$file");
		}
	}

	if ($empty) {
		return true;
	}
	
	return rmdir($dir);
}

/**
 * Deletes all cached views in the simplecache and sets the lastcache and
 * lastupdate time to 0 for every valid viewtype.
 *
 * @return bool
 * @since 1.7.4
 */
function elgg_invalidate_simplecache() {
	_elgg_services()->simpleCache->invalidate();
}

/**
 * Flush all the registered caches
 *
 * @return void
 * @since 1.11
 */
function elgg_flush_caches() {
	_elgg_services()->events->triggerSequence('cache:flush', 'system');
}

/**
 * Checks if /cache directory has been symlinked to views simplecache directory
 *
 * @return bool
 * @access private
 */
function _elgg_is_cache_symlinked() {
	$root_path = elgg_get_root_path();

	$simplecache_path = elgg_get_asset_path();
	$symlink_path = "{$root_path}cache";

	if (!is_dir($simplecache_path)) {
		return false;
	}
	return is_dir($symlink_path) && realpath($simplecache_path) == realpath($symlink_path);
}

/**
 * Symlinks /cache directory to views simplecache directory
 *
 * @return bool
 * @access private
 */
function _elgg_symlink_cache() {

	if (_elgg_is_cache_symlinked()) {
		// Symlink exists, no need to proceed
		return true;
	}

	$root_path = elgg_get_root_path();
	$simplecache_path = rtrim(elgg_get_asset_path(), '/');
	$symlink_path = "{$root_path}cache";

	if (is_dir($symlink_path)) {
		// Cache directory already exists
		// We can not proceed without overwriting files
		return false;
	}

	if (!is_dir($simplecache_path)) {
		// Views simplecache directory has not yet been created
		mkdir($simplecache_path, 0755, true);
	}

	symlink($simplecache_path, $symlink_path);

	if (_elgg_is_cache_symlinked()) {
		return true;
	}

	if (is_dir($symlink_path)) {
		unlink($symlink_path);
	}
	
	return false;
}

/**
 * Initializes the simplecache lastcache variable and creates system cache files
 * when appropriate.
 *
 * @return void
 *
 * @access private
 */
function _elgg_cache_init() {
	_elgg_services()->systemCache->init();
}

/**
 * Disable all caches
 *
 * @return void
 * @internal
 * @access private
 */
function _elgg_disable_caches() {
	_elgg_services()->boot->getCache()->disable();
	_elgg_services()->plugins->getCache()->disable();
	_elgg_services()->sessionCache->disable();
	_elgg_services()->dataCache->disable();
	_elgg_services()->dic_cache->getCache()->disable();
	_elgg_services()->autoloadManager->getCache()->disable();
	_elgg_services()->systemCache->getCache()->disable();
}

/**
 * Clear all caches
 *
 * @return void
 * @internal
 * @access private
 */
function _elgg_clear_caches() {
	_elgg_services()->boot->invalidateCache();
	_elgg_services()->plugins->clear();
	_elgg_services()->sessionCache->clear();
	_elgg_services()->dataCache->clear();
	_elgg_services()->dic_cache->flushAll();
	_elgg_services()->simpleCache->invalidate();
	_elgg_services()->autoloadManager->deleteCache();
	_elgg_services()->fileCache->clear();
}

/**
 * Resets OPcache
 *
 * @return void
 * @internal
 * @access private
 */
function _elgg_reset_opcache() {
	if (!function_exists('opcache_reset')) {
		return;
	}
	
	opcache_reset();
}

/**
 * Enable all caches
 *
 * @return void
 * @internal
 * @access private
 */
function _elgg_enable_caches() {
	_elgg_services()->boot->getCache()->enable();
	_elgg_services()->plugins->getCache()->enable();
	_elgg_services()->sessionCache->enable();
	_elgg_services()->dataCache->enable();
	_elgg_services()->dic_cache->getCache()->enable();
	_elgg_services()->autoloadManager->getCache()->enable();
	_elgg_services()->systemCache->getCache()->enable();
}

/**
 * Rebuild public service container
 *
 * @return void
 * @internal
 * @access private
 */
function _elgg_rebuild_public_container() {
	$services = _elgg_services();

	$dic_builder = new \DI\ContainerBuilder(\Elgg\Di\PublicContainer::class);
	$dic_builder->useAnnotations(false);
	$dic_builder->setDefinitionCache($services->dic_cache);

	$definitions = $services->dic_loader->getDefinitions();
	foreach ($definitions as $definition) {
		$dic_builder->addDefinitions($definition);
	}

	$dic = $dic_builder->build();

	_elgg_services()->setValue('dic_builder', $dic_builder);
	_elgg_services()->setValue('dic', $dic);
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('ready', 'system', '_elgg_cache_init');

	$events->registerHandler('cache:flush:before', 'system', '_elgg_disable_caches');
	$events->registerHandler('cache:flush', 'system', '_elgg_clear_caches');
	$events->registerHandler('cache:flush', 'system', '_elgg_reset_opcache');
	$events->registerHandler('cache:flush:after', 'system', '_elgg_enable_caches');
	$events->registerHandler('cache:flush:after', 'system', '_elgg_rebuild_public_container');
};
