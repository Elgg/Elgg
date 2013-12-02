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
	global $CONFIG;

	static $memcache_available;

	if ((!isset($CONFIG->memcache)) || (!$CONFIG->memcache)) {
		return false;
	}

	// If we haven't set variable to something
	if (($memcache_available !== true) && ($memcache_available !== false)) {
		try {
			$tmp = new ElggMemcache();
			// No exception thrown so we have memcache available
			$memcache_available = true;
		} catch (Exception $e) {
			$memcache_available = false;
		}
	}

	return $memcache_available;
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
	static $newentity_cache;
	
	if ((!$newentity_cache) && (is_memcache_available())) {
		$newentity_cache = new ElggMemcache('new_entity_cache');
	}
	
	if ($newentity_cache) {
		$newentity_cache->delete($entity_guid);
	}
}