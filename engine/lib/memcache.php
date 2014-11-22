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
 * @return ElggMemcache|ElggNullCache
 * @access private
 * @since 1.10
 */
function _elgg_get_memcache($namespace = 'default') {
	static $cache_pool = 'initial';
	static $null_cache;
	static $objects = array();

	// can't use null because the service may be null
	if ($cache_pool === 'initial') {
		$cache_pool = _elgg_services()->memcacheStashPool;
	}

	if (!$cache_pool) {
		if ($null_cache === null) {
			$null_cache = new ElggNullCache();
		}
		return $null_cache;
	}

	if (!isset($objects[$namespace])) {
		$objects[$namespace] = new ElggMemcache($namespace, $cache_pool);
	}
	return $objects[$namespace];
}
