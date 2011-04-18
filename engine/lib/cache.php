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

/* Simplecache */

/**
 * Registers a view to simple cache.
 *
 * Simple cache is a caching mechanism that saves the output of
 * views and its extensions into a file.  If the view is called
 * by the {@link simplecache/view.php} file, the Elgg framework will
 * not be loaded and the contents of the view will returned
 * from file.
 *
 * @warning Simple cached views must take no parameters and return
 * the same content no matter who is logged in.
 *
 * @note CSS and the basic JS views are cached by the engine.
 *
 * @param string $viewname View name
 *
 * @return void
 * @link http://docs.elgg.org/Views/Simplecache
 * @see elgg_regenerate_simplecache()
 * @since 1.8.0
 */
function elgg_register_simplecache_view($viewname) {
	global $CONFIG;

	if (!isset($CONFIG->views)) {
		$CONFIG->views = new stdClass;
	}

	if (!isset($CONFIG->views->simplecache)) {
		$CONFIG->views->simplecache = array();
	}

	$CONFIG->views->simplecache[] = $viewname;
}

/**
 * Get the URL for the cached file
 *
 * @param string $type The file type: css or js
 * @param string $view The view name
 * @return string
 * @since 1.8.0
 */
function elgg_get_simplecache_url($type, $view) {
	global $CONFIG;
	$lastcache = (int)$CONFIG->lastcache;
	$viewtype = elgg_get_viewtype();
	if (elgg_is_simplecache_enabled()) {
		$url = elgg_get_site_url() . "cache/$type/$viewtype/$view.$lastcache.$type";
	} else {
		$url = elgg_get_site_url() . "$type/$view.$lastcache.$type";
		$elements = array("view" => $viewtype);
		$url = elgg_http_add_url_query_elements($url, $elements);
	}
	
	return $url;
}

/**
 * Regenerates the simple cache.
 *
 * @warning This does not invalidate the cache, but actively resets it.
 *
 * @param string $viewtype Optional viewtype to regenerate
 *
 * @return void
 * @see elgg_register_simplecache_view()
 * @since 1.8.0
 */
function elgg_regenerate_simplecache($viewtype = NULL) {
	global $CONFIG;

	if (!isset($CONFIG->views->simplecache) || !is_array($CONFIG->views->simplecache)) {
		return;
	}

	$lastcached = time();

	// @todo elgg_view() checks if the page set is done (isset($CONFIG->pagesetupdone)) and
	// triggers an event if it's not. Calling elgg_view() here breaks submenus
	// (at least) because the page setup hook is called before any
	// contexts can be correctly set (since this is called before page_handler()).
	// To avoid this, lie about $CONFIG->pagehandlerdone to force
	// the trigger correctly when the first view is actually being output.
	$CONFIG->pagesetupdone = TRUE;

	if (!file_exists($CONFIG->dataroot . 'views_simplecache')) {
		mkdir($CONFIG->dataroot . 'views_simplecache');
	}

	if (isset($viewtype)) {
		$viewtypes = array($viewtype);
	} else {
		$viewtypes = $CONFIG->view_types;
	}

	$original_viewtype = elgg_get_viewtype();

	// disable error reporting so we don't cache problems
	$old_debug = elgg_get_config('debug');
	elgg_set_config('debug', null);

	foreach ($viewtypes as $viewtype) {
		elgg_set_viewtype($viewtype);
		foreach ($CONFIG->views->simplecache as $view) {
			$viewcontents = elgg_view($view);
			$viewname = md5(elgg_get_viewtype() . $view);
			if ($handle = fopen($CONFIG->dataroot . 'views_simplecache/' . $viewname, 'w')) {
				fwrite($handle, $viewcontents);
				fclose($handle);
			}
		}

		datalist_set("simplecache_lastupdate_$viewtype", $lastcached);
		datalist_set("simplecache_lastcached_$viewtype", $lastcached);
	}

	elgg_set_config('debug', $old_debug);
	elgg_set_viewtype($original_viewtype);

	// needs to be set for links in html head
	$CONFIG->lastcache = $lastcached;

	unset($CONFIG->pagesetupdone);
}

/**
 * Is simple cache enabled
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_is_simplecache_enabled() {
	if (elgg_get_config('simplecache_enabled')) {
		return true;
	}

	return false;
}

/**
 * Enables the simple cache.
 *
 * @access private
 * @see elgg_register_simplecache_view()
 * @return void
 * @since 1.8.0
 */
function elgg_enable_simplecache() {
	global $CONFIG;

	datalist_set('simplecache_enabled', 1);
	$CONFIG->simplecache_enabled = 1;
	elgg_regenerate_simplecache();
}

/**
 * Disables the simple cache.
 *
 * @warning Simplecache is also purged when disabled.
 *
 * @access private
 * @see elgg_register_simplecache_view()
 * @return void
 * @since 1.8.0
 */
function elgg_disable_simplecache() {
	global $CONFIG;
	if ($CONFIG->simplecache_enabled) {
		datalist_set('simplecache_enabled', 0);
		$CONFIG->simplecache_enabled = 0;

		// purge simple cache
		if ($handle = opendir($CONFIG->dataroot . 'views_simplecache')) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					unlink($CONFIG->dataroot . 'views_simplecache/' . $file);
				}
			}
			closedir($handle);
		}
	}
}

/**
 * Invalidates all cached views in the simplecache
 *
 * @return bool
 * @since 1.7.4
 */
function elgg_invalidate_simplecache() {
	global $CONFIG;

	$return = TRUE;

	if ($handle = opendir($CONFIG->dataroot . 'views_simplecache')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				$return = $return && unlink($CONFIG->dataroot . 'views_simplecache/' . $file);
			}
		}
		closedir($handle);
	} else {
		$return = FALSE;
	}

	return $return;
}

