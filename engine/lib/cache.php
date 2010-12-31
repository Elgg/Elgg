<?php
/**
 * Elgg cache
 * Cache file interface for caching data.
 *
 * @package    Elgg.Core
 * @subpackage Cache
 */

/**
 * Returns an ElggCache object suitable for caching view
 * file load paths to disk under $CONFIG->dataroot.
 *
 * @todo Can this be done in a cleaner way?
 * @todo Swap to memcache etc?
 *
 * @return ElggFileCache A cache object suitable for caching file load paths.
 */
function elgg_get_filepath_cache() {
	global $CONFIG;

	/**
	 * A default filestore cache using the dataroot.
	 */
	static $FILE_PATH_CACHE;

	if (!$FILE_PATH_CACHE) {
		$FILE_PATH_CACHE = new ElggFileCache($CONFIG->dataroot);
	}

	return $FILE_PATH_CACHE;
}

/**
 * Function which resets the file path cache.
 *
 */
function elgg_filepath_cache_reset() {
	$cache = elgg_get_filepath_cache();
	$view_types_result = $cache->delete('view_types');
	$views_result = $cache->delete('views');
	return $view_types_result && $views_result;
}

/**
 * Saves a filepath cache.
 *
 * @param string $type
 * @param string $data
 * @return bool
 */
function elgg_filepath_cache_save($type, $data) {
	global $CONFIG;

	if ($CONFIG->viewpath_cache_enabled) {
		$cache = elgg_get_filepath_cache();
		return $cache->save($type, $data);
	}

	return false;
}

/**
 * Retrieve the contents of the filepath cache.
 *
 * @param string $type The type of cache to load
 * @return string
 */
function elgg_filepath_cache_load($type) {
	global $CONFIG;

	if ($CONFIG->viewpath_cache_enabled) {
		$cache = elgg_get_filepath_cache();
		$cached_data = $cache->load($type);

		if ($cached_data) {
			return $cached_data;
		}
	}

	return NULL;
}

/**
 * Enables the views file paths disk cache.
 *
 * Uses the 'viewpath_cache_enabled' datalist with a boolean value.
 * Resets the views paths cache.
 *
 * @return null
 */
function elgg_enable_filepath_cache() {
	global $CONFIG;

	datalist_set('viewpath_cache_enabled', 1);
	$CONFIG->viewpath_cache_enabled = 1;
	elgg_filepath_cache_reset();
}

/**
 * Disables the views file paths disk cache.
 *
 * Uses the 'viewpath_cache_enabled' datalist with a boolean value.
 * Resets the views paths cache.
 *
 * @return null
 */
function elgg_disable_filepath_cache() {
	global $CONFIG;

	datalist_set('viewpath_cache_enabled', 0);
	$CONFIG->viewpath_cache_enabled = 0;
	elgg_filepath_cache_reset();
}
