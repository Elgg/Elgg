<?php
/**
 * Elgg cache
 * Cache file interface for caching data.
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
 * @param mixed  $data The data to be saved
 *
 * @return bool
 */
function elgg_save_system_cache($type, $data) {
	return _elgg_services()->systemCache->save($type, $data);
}

/**
 * Retrieve the contents of a system cache.
 *
 * @param string $type The type of cache to load
 *
 * @return mixed null if key not found in cache
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
 * Invalidate all the registered caches
 *
 * @return void
 * @since 3.3
 */
function elgg_invalidate_caches() {
	// this event sequence could take while, make sure there is no timeout
	set_time_limit(0);
	
	elgg()->config->save('lastcache', time());
	
	_elgg_services()->events->triggerSequence('cache:invalidate', 'system');
}

/**
 * Clear all the registered caches
 *
 * @return void
 * @since 3.3
 */
function elgg_clear_caches() {
	// this event sequence could take while, make sure there is no timeout
	set_time_limit(0);
	
	_elgg_services()->events->triggerSequence('cache:clear', 'system');
}

/**
 * Purge all the registered caches
 *
 * This will remove all old/stale items from the caches
 *
 * @return void
 * @since 3.3
 */
function elgg_purge_caches() {
	// this event sequence could take while, make sure there is no timeout
	set_time_limit(0);
	
	_elgg_services()->events->triggerSequence('cache:purge', 'system');
}

/**
 * Checks if /cache directory has been symlinked to views simplecache directory
 *
 * @return bool
 * @internal
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
 * @internal
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
 * Initializes caches
 *
 * @return void
 * @internal
 */
function _elgg_cache_init() {
	_elgg_services()->systemCache->init();
}

/**
 * Disable all caches
 *
 * @return void
 * @internal
 */
function _elgg_disable_caches() {
	_elgg_services()->boot->getCache()->disable();
	_elgg_services()->plugins->getCache()->disable();
	_elgg_services()->sessionCache->disable();
	_elgg_services()->dataCache->disable();
	_elgg_services()->dic_cache->getCache()->disable();
	_elgg_services()->autoloadManager->getCache()->disable();
	_elgg_services()->systemCache->getCache()->disable();
	_elgg_services()->serverCache->getCache()->disable();
}

/**
 * Clear all caches
 *
 * @return void
 * @internal
 */
function _elgg_clear_caches() {
	_elgg_services()->boot->clearCache();
	_elgg_services()->plugins->clear();
	_elgg_services()->sessionCache->clear();
	_elgg_services()->dataCache->clear();
	_elgg_services()->dic_cache->flushAll();
	_elgg_services()->simpleCache->clear();
	_elgg_services()->autoloadManager->deleteCache();
	_elgg_services()->fileCache->clear();
	_elgg_services()->localFileCache->clear();
}

/**
 * Invalidate all caches
 *
 * @return void
 * @internal
 */
function _elgg_invalidate_caches() {
	_elgg_services()->boot->getCache()->invalidate();
	_elgg_services()->plugins->invalidate();
	_elgg_services()->sessionCache->invalidate();
	_elgg_services()->dataCache->invalidate();
	_elgg_services()->dic_cache->getCache()->invalidate();
	_elgg_services()->simpleCache->invalidate(false);
	_elgg_services()->fileCache->invalidate();
	_elgg_services()->localFileCache->invalidate();
}

/**
 * Purge all caches
 *
 * @return void
 * @internal
 */
function _elgg_purge_caches() {
	_elgg_services()->boot->getCache()->purge();
	_elgg_services()->plugins->getCache()->purge();
	_elgg_services()->sessionCache->purge();
	_elgg_services()->dataCache->purge();
	_elgg_services()->dic_cache->getCache()->purge();
	_elgg_services()->simpleCache->purge();
	_elgg_services()->fileCache->purge();
	_elgg_services()->localFileCache->purge();
}

/**
 * Resets OPcache
 *
 * @return void
 * @internal
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
 */
function _elgg_enable_caches() {
	_elgg_services()->boot->getCache()->enable();
	_elgg_services()->plugins->getCache()->enable();
	_elgg_services()->sessionCache->enable();
	_elgg_services()->dataCache->enable();
	_elgg_services()->dic_cache->getCache()->enable();
	_elgg_services()->autoloadManager->getCache()->enable();
	_elgg_services()->systemCache->getCache()->enable();
	_elgg_services()->serverCache->getCache()->enable();
}

/**
 * Rebuild public service container
 *
 * @return void
 * @internal
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

	$events->registerHandler('cache:clear:before', 'system', '_elgg_disable_caches');
	$events->registerHandler('cache:clear', 'system', '_elgg_clear_caches');
	$events->registerHandler('cache:clear', 'system', '_elgg_reset_opcache');
	$events->registerHandler('cache:clear:after', 'system', '_elgg_enable_caches');
	$events->registerHandler('cache:clear:after', 'system', '_elgg_rebuild_public_container');
	$events->registerHandler('cache:invalidate', 'system', '_elgg_invalidate_caches');
	$events->registerHandler('cache:purge', 'system', '_elgg_purge_caches');
};
