<?php
/**
 * Elgg memcache support.
 *
 * Requires php5-memcache to work.
 *
 * @package Elgg.Core
 * @subpackage Cache.Memcache
 */

/**
 * Return true if memcache is available and configured.
 *
 * @return bool
 */
function is_memcache_available() {
	return (bool) _elgg_services()->memcacheStashPool;
}

/**
 * Invalidate an entity in memcache
 *
 * @param int $entity_guid The GUID of the entity to invalidate
 *
 * @return void
 * @access private
 */
function _elgg_invalidate_memcache_for_entity($entity_guid) {
	_elgg_get_memcache('new_entity_cache')->delete($entity_guid);
}

/**
 * Get a namespaced ElggMemcache object (if memcache is available) or a null cache
 *
 * @param string $namespace Namespace to add to all keys used
 *
 * @return ElggMemcache|Elgg\Cache\NullCache
 * @access private
 */
function _elgg_get_memcache($namespace = 'default') {
	static $caches = array();

	$cache_pool = _elgg_services()->memcacheStashPool;
	if (!$cache_pool) {
		return _elgg_services()->nullCache;
	}

	if (!isset($caches[$namespace])) {
		$caches[$namespace] = new ElggMemcache($namespace, $cache_pool);
	}
	return $caches[$namespace];
}
