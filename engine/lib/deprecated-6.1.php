<?php
/**
 * Bundle all functions which have been deprecated in Elgg 6.1
 */

/* @deprecated 6.1 Use \Elgg\Cache\CompositeCache::CACHE_BLACK_HOLE */
const ELGG_CACHE_BLACK_HOLE = 1;
/* @deprecated 6.1 Use \Elgg\Cache\CompositeCache::CACHE_RUNTIME */
const ELGG_CACHE_RUNTIME = 2;
/* @deprecated 6.1 Use \Elgg\Cache\CompositeCache::CACHE_FILESYSTEM */
const ELGG_CACHE_FILESYSTEM = 4;
/* @deprecated 6.1 Use \Elgg\Cache\CompositeCache::CACHE_PERSISTENT */
const ELGG_CACHE_PERSISTENT = 8;
/* @deprecated 6.1 Use \Elgg\Cache\CompositeCache::CACHE_LOCALFILESYSTEM */
const ELGG_CACHE_LOCALFILESYSTEM = 32;

/**
 * Returns an \Elgg\Cache\BaseCache object suitable for caching system information
 *
 * @return \Elgg\Cache\BaseCache
 * @deprecated 6.1
 */
function elgg_get_system_cache(): \Elgg\Cache\BaseCache {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '6.1');
	
	return _elgg_services()->systemCache->getCache();
}

/**
 * Reset the system cache by deleting the caches
 *
 * @return void
 * @deprecated 6.1
 */
function elgg_reset_system_cache(): void {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '6.1');
	
	_elgg_services()->systemCache->clear();
}

/**
 * Is system cache enabled
 *
 * @return bool
 * @since 2.2.0
 * @deprecated 6.1
 */
function elgg_is_system_cache_enabled(): bool {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '6.1');
	
	return _elgg_services()->systemCache->isEnabled();
}

/**
 * Enables the system disk cache.
 *
 * Uses the 'system_cache_enabled' config with a boolean value.
 * Resets the system cache.
 *
 * @return void
 * @deprecated 6.1
 */
function elgg_enable_system_cache(): void {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '6.1');
	
	_elgg_services()->systemCache->enable();
}

/**
 * Disables the system disk cache.
 *
 * Uses the 'system_cache_enabled' config with a boolean value.
 * Resets the system cache.
 *
 * @return void
 * @deprecated 6.1
 */
function elgg_disable_system_cache(): void {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '6.1');
	
	_elgg_services()->systemCache->disable();
}

/**
 * Is simple cache enabled
 *
 * @return bool
 * @since 1.8.0
 * @deprecated 6.1
 */
function elgg_is_simplecache_enabled(): bool {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '6.1');
	
	return _elgg_services()->simpleCache->isEnabled();
}

/**
 * Enables the simple cache.
 *
 * @return void
 * @since 1.8.0
 * @deprecated 6.1
 */
function elgg_enable_simplecache(): void {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '6.1');
	
	_elgg_services()->simpleCache->enable();
}

/**
 * Disables the simple cache.
 *
 * @warning Simplecache is also purged when disabled.
 *
 * @return void
 * @since 1.8.0
 * @deprecated 6.1
 */
function elgg_disable_simplecache(): void {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '6.1');
	
	_elgg_services()->simpleCache->disable();
}

/**
 * Returns if a plugin exists in the system.
 *
 * @warning This checks only plugins that are registered in the system!
 * If the plugin cache is outdated, be sure to regenerate it with
 * {@link _elgg_generate_plugin_objects()} first.
 *
 * @param string $plugin_id The plugin ID.
 *
 * @return bool
 * @since 1.8.0
 * @deprecated 6.1 Use elgg_get_plugin_from_id()
 */
function elgg_plugin_exists(string $plugin_id): bool {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_get_plugin_from_id().', '6.1');
	
	return _elgg_services()->plugins->get($plugin_id) instanceof \ElggPlugin;
}
