<?php
/**
 * Elgg cache
 * Cache file interface for caching data.
 *
 * @package    Elgg.Core
 * @subpackage Cache
 */

/* Filepath Cache */

/**
 * Returns an ElggCache object suitable for caching system information
 *
 * @todo Can this be done in a cleaner way?
 * @todo Swap to memcache etc?
 *
 * @return ElggFileCache
 */
function elgg_get_system_cache() {
	global $CONFIG;

	/**
	 * A default filestore cache using the dataroot.
	 */
	static $FILE_PATH_CACHE;

	if (!$FILE_PATH_CACHE) {
		$FILE_PATH_CACHE = new ElggFileCache($CONFIG->dataroot . 'system_cache/');
	}

	return $FILE_PATH_CACHE;
}

/**
 * Reset the system cache by deleting the caches
 *
 * @return void
 */
function elgg_reset_system_cache() {
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
function elgg_save_system_cache($type, $data) {
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
function elgg_load_system_cache($type) {
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
function elgg_enable_system_cache() {
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
function elgg_disable_system_cache() {
	global $CONFIG;

	datalist_set('system_cache_enabled', 0);
	$CONFIG->system_cache_enabled = 0;
	elgg_reset_system_cache();
}

/* Simplecache */

/**
 * Registers a view to simple cache.
 *
 * Simple cache is a caching mechanism that saves the output of
 * a view and its extensions into a file.  If the view is called
 * by the {@link engine/handlers/cache_handler.php} file, the Elgg
 * engine will not be loaded and the contents of the view will returned
 * from file.
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
function elgg_register_simplecache_view($view_name) {
	elgg_register_external_view($view_name, true);
}

/**
 * Get the URL for the cached file
 *
 * This automatically registers the view with Elgg's simplecache.
 *
 * @example
 * 		$blog_js = elgg_get_simplecache_url('js', 'blog/save_draft');
 *		elgg_register_js('elgg.blog', $blog_js);
 *		elgg_load_js('elgg.blog');
 *
 * @param string $type The file type: css or js
 * @param string $view The view name after css/ or js/
 * @return string
 * @since 1.8.0
 */
function elgg_get_simplecache_url($type, $view) {
	// handle file type passed with view name
	if (($type === 'js' || $type === 'css') && 0 === strpos($view, $type . '/')) {
		$view = substr($view, strlen($type) + 1);
	}

	elgg_register_simplecache_view("$type/$view");
	return _elgg_get_simplecache_root() . "$type/$view";
}


/**
 * Get the base url for simple cache requests
 *
 * @return string The simplecache root url for the current viewtype.
 * @access private
 */
function _elgg_get_simplecache_root() {
	$viewtype = elgg_get_viewtype();
	if (elgg_is_simplecache_enabled()) {
		// stored in datalist as 'simplecache_lastupdate'
		$lastcache = (int)elgg_get_config('lastcache');
	} else {
		$lastcache = 0;
	}

	return elgg_normalize_url("/cache/$lastcache/$viewtype/");
}

/**
 * Returns the type of output expected from the view.
 *
 * css/* views always return "css"
 * js/* views always return "js"
 *
 * @todo why isn't this in the CacheHandler class? It is not used anywhere else.
 *
 * @todo view/name.suffix returns "suffix"
 *
 * Otherwise, returns "unknown"
 *
 * @param string $view The view name
 * @return string
 * @access private
 */
function _elgg_get_view_filetype($view) {
	if (preg_match('~(?:^|/)(css|js)(?:$|/)~', $view, $m)) {
		return $m[1];
	} else {
		return 'unknown';
	}
}

/**
 * Is simple cache enabled
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_is_simplecache_enabled() {
	return (bool) elgg_get_config('simplecache_enabled');
}

/**
 * Enables the simple cache.
 *
 * @see elgg_register_simplecache_view()
 * @return void
 * @since 1.8.0
 */
function elgg_enable_simplecache() {
	datalist_set('simplecache_enabled', 1);
	elgg_set_config('simplecache_enabled', 1);
	elgg_invalidate_simplecache();
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
function elgg_disable_simplecache() {
	if (elgg_get_config('simplecache_enabled')) {
		datalist_set('simplecache_enabled', 0);
		elgg_set_config('simplecache_enabled', 0);

		// purge simple cache
		_elgg_rmdir(elgg_get_data_path() . "views_simplecache");
	}
}

/**
 * Recursively deletes a directory, including all hidden files.
 *
 * @param string $dir
 * @return boolean Whether the dir was successfully deleted.
 * @access private
 */
function _elgg_rmdir($dir) {
	$files = array_diff(scandir($dir), array('.', '..'));

	foreach ($files as $file) {
		if (is_dir("$dir/$file")) {
			_elgg_rmdir("$dir/$file");
		} else {
			unlink("$dir/$file");
		}
	}

	return rmdir($dir);
}

/**
 * Deletes all cached views in the simplecache and sets the lastcache and
 * lastupdate time to 0 for every valid viewtype.
 *
 * @return bool
 * @since 1.7.4
 */
function elgg_invalidate_simplecache() {
	global $CONFIG;

	if (!isset($CONFIG->views->simplecache) || !is_array($CONFIG->views->simplecache)) {
		return false;
	}

	_elgg_rmdir("{$CONFIG->dataroot}views_simplecache");
	mkdir("{$CONFIG->dataroot}views_simplecache");

	$time = time();
	datalist_set("simplecache_lastupdate", $time);
	$CONFIG->lastcache = $time;

	return true;
}

/**
 * Loads the system cache during engine boot
 *
 * @see elgg_reset_system_cache()
 * @access private
 */
function _elgg_load_cache() {
	global $CONFIG;

	$CONFIG->system_cache_loaded = false;

	$CONFIG->views = new stdClass();
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
function _elgg_cache_init() {
	global $CONFIG;

	if (!defined('UPGRADING') && empty($CONFIG->lastcache)) {
		$CONFIG->lastcache = (int)datalist_get('simplecache_lastupdate');
	}

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

elgg_register_event_handler('ready', 'system', '_elgg_cache_init');
