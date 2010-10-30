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
 * Deletes the view file paths cache from disk.
 *
 * @return bool On success
 */
function elgg_filepath_cache_reset() {
	$cache = elgg_get_filepath_cache();
	return $cache->delete('view_paths');
}

/**
 * Saves $data to the views file paths disk cache as
 * 'view_paths'.
 *
 * @param mixed $data The data
 *
 * @return bool On success
 */
function elgg_filepath_cache_save($data) {
	global $CONFIG;

	if ($CONFIG->viewpath_cache_enabled) {
		$cache = elgg_get_filepath_cache();
		return $cache->save('view_paths', $data);
	}

	return false;
}

/**
 * Returns the contents of the views file paths cache from disk.
 *
 * @return mixed Null if simplecache isn't enabled, the contents of the
 * views file paths cache if it is.
 */
function elgg_filepath_cache_load() {
	global $CONFIG;

	if ($CONFIG->viewpath_cache_enabled) {
		$cache = elgg_get_filepath_cache();
		$cached_view_paths = $cache->load('view_paths');

		if ($cached_view_paths) {
			return $cached_view_paths;
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
