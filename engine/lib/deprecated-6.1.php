<?php
/**
 * Bundle all functions which have been deprecated in Elgg 6.1
 */

/**
 * Returns an \Elgg\Cache\BaseCache object suitable for caching system information
 *
 * @return \Elgg\Cache\BaseCache
 * @deprecated 6.1
 */
function elgg_get_system_cache(): \Elgg\Cache\BaseCache {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '6.1');
	
	return _elgg_services()->fileCache;
}

/**
 * Reset the system cache by deleting the caches
 *
 * @return void
 * @deprecated 6.1
 */
function elgg_reset_system_cache(): void {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '6.1');
	
	_elgg_services()->systemCache->reset();
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
