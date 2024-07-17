<?php
/**
 * Elgg cache
 * Cache file interface for caching data.
 */

/**
 * Saves a system cache.
 *
 * @param string $type         The type or identifier of the cache
 * @param mixed  $data         The data to be saved
 * @param int    $expire_after Number of seconds to expire the cache after
 *
 * @return bool
 */
function elgg_save_system_cache(string $type, $data, int $expire_after = null): bool {
	return _elgg_services()->systemCache->save($type, $data, $expire_after);
}

/**
 * Retrieve the contents of a system cache.
 *
 * @param string $type The type of cache to load
 *
 * @return mixed null if key not found in cache
 */
function elgg_load_system_cache(string $type) {
	return _elgg_services()->systemCache->load($type);
}

/**
 * Deletes the contents of a system cache.
 *
 * @param string $type The type of cache to delete
 * @return bool
 * @since 3.0
 */
function elgg_delete_system_cache(string $type): bool {
	return _elgg_services()->systemCache->delete($type);
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
function elgg_register_simplecache_view(string $view_name): void {
	_elgg_services()->simpleCache->registerCacheableView($view_name);
}

/**
 * Get the URL for the cached view.
 *
 * ```
 * $blog_js = elgg_get_simplecache_url('elgg/blog/save_draft.js');
 * $favicon = elgg_get_simplecache_url('favicon.ico');
 * ```
 *
 * This automatically registers the view with Elgg's simplecache.
 *
 * @param string $view The full view name
 *
 * @return string
 * @since 1.8.0
 */
function elgg_get_simplecache_url(string $view): string {
	return _elgg_services()->simpleCache->getUrl($view);
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
	
	_elgg_services()->config->save('lastcache', time());
	
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
