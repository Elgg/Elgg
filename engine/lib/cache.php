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
function elgg_get_system_cache(): \ElggCache {
	return _elgg_services()->fileCache;
}

/**
 * Reset the system cache by deleting the caches
 *
 * @return void
 */
function elgg_reset_system_cache(): void {
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
function elgg_save_system_cache($type, $data): bool {
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
function elgg_delete_system_cache($type): bool {
	return _elgg_services()->systemCache->delete($type);
}

/**
 * Is system cache enabled
 *
 * @return bool
 * @since 2.2.0
 */
function elgg_is_system_cache_enabled(): bool {
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
function elgg_enable_system_cache(): void {
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
function elgg_disable_system_cache(): void {
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
function elgg_register_simplecache_view($view_name): void {
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
function elgg_get_simplecache_url($view, $subview = ''): string {
	return _elgg_services()->simpleCache->getUrl($view, $subview);
}

/**
 * Is simple cache enabled
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_is_simplecache_enabled(): bool {
	return _elgg_services()->simpleCache->isEnabled();
}

/**
 * Enables the simple cache.
 *
 * @see elgg_register_simplecache_view()
 * @return void
 * @since 1.8.0
 */
function elgg_enable_simplecache(): void {
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
function elgg_disable_simplecache(): void {
	_elgg_services()->simpleCache->disable();
}

/**
 * Invalidate all the registered caches
 *
 * @return void
 * @since 3.3
 */
function elgg_invalidate_caches(): void {
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
function elgg_clear_caches(): void {
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
function elgg_purge_caches(): void {
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
function _elgg_is_cache_symlinked(): bool {
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
function _elgg_symlink_cache(): bool {

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
