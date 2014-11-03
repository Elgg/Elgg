<?php
namespace Elgg\Cache;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Cache
 * @since      1.10.0
 */
class SystemCache {
	/**
	 * Returns an \ElggCache object suitable for caching system information
	 *
	 * @todo Can this be done in a cleaner way?
	 * @todo Swap to memcache etc?
	 *
	 * @return \ElggFileCache
	 */
	function get() {
		global $CONFIG;
	
		/**
		 * A default filestore cache using the dataroot.
		 */
		static $FILE_PATH_CACHE;
	
		if (!$FILE_PATH_CACHE) {
			$FILE_PATH_CACHE = new \ElggFileCache($CONFIG->dataroot . 'system_cache/');
		}
	
		return $FILE_PATH_CACHE;
	}
	
	/**
	 * Reset the system cache by deleting the caches
	 *
	 * @return void
	 */
	function reset() {
		$cache = elgg_get_system_cache();
		$cache->clear();
	}
	
	/**
	 * Saves a system cache.
	 *
	 * @param string $type The type or identifier of the cache
	 * @param string $data The data to be saved
	 * @return bool
	 */
	function save($type, $data) {
		global $CONFIG;
	
		if ($CONFIG->system_cache_enabled) {
			$cache = elgg_get_system_cache();
			return $cache->save($type, $data);
		}
	
		return false;
	}
	
	/**
	 * Retrieve the contents of a system cache.
	 *
	 * @param string $type The type of cache to load
	 * @return string
	 */
	function load($type) {
		global $CONFIG;
	
		if ($CONFIG->system_cache_enabled) {
			$cache = elgg_get_system_cache();
			$cached_data = $cache->load($type);
	
			if ($cached_data) {
				return $cached_data;
			}
		}
	
		return null;
	}
	
	/**
	 * Enables the system disk cache.
	 *
	 * Uses the 'system_cache_enabled' datalist with a boolean value.
	 * Resets the system cache.
	 *
	 * @return void
	 */
	function enable() {
		global $CONFIG;
	
		datalist_set('system_cache_enabled', 1);
		$CONFIG->system_cache_enabled = 1;
		elgg_reset_system_cache();
	}
	
	/**
	 * Disables the system disk cache.
	 *
	 * Uses the 'system_cache_enabled' datalist with a boolean value.
	 * Resets the system cache.
	 *
	 * @return void
	 */
	function disable() {
		global $CONFIG;
	
		datalist_set('system_cache_enabled', 0);
		$CONFIG->system_cache_enabled = 0;
		elgg_reset_system_cache();
	}
	
	/**
	 * Loads the system cache during engine boot
	 * 
	 * @see elgg_reset_system_cache()
	 * @access private
	 */
	function loadAll() {
		global $CONFIG;
	
		$CONFIG->system_cache_loaded = false;
	
		$CONFIG->views = new \stdClass();
		$data = elgg_load_system_cache('view_locations');
		if (!is_string($data)) {
			return;
		}
		$CONFIG->views->locations = unserialize($data);
		
		$data = elgg_load_system_cache('view_types');
		if (!is_string($data)) {
			return;
		}
		$CONFIG->view_types = unserialize($data);
	
		$CONFIG->system_cache_loaded = true;
	}
	
	/**
	 * Initializes the simplecache lastcache variable and creates system cache files
	 * when appropriate.
	 * 
	 * @access private
	 */
	function init() {
		// cache system data if enabled and not loaded
		if ($CONFIG->system_cache_enabled && !$CONFIG->system_cache_loaded) {
			elgg_save_system_cache('view_locations', serialize($CONFIG->views->locations));
			elgg_save_system_cache('view_types', serialize($CONFIG->view_types));
		}
	
		if ($CONFIG->system_cache_enabled && !$CONFIG->i18n_loaded_from_cache) {
			reload_all_translations();
			foreach ($CONFIG->translations as $lang => $map) {
				elgg_save_system_cache("$lang.lang", serialize($map));
			}
		}
	}
}