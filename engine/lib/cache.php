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

	return NULL;
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

/** @todo deprecate in Elgg 1.9 **/

/**
 * @access private
 */
function elgg_get_filepath_cache() {
	return elgg_get_system_cache();
}
/**
 * @access private
 */
function elgg_filepath_cache_reset() {
	elgg_reset_system_cache();
}
/**
 * @access private
 */
function elgg_filepath_cache_save($type, $data) {
	return elgg_save_system_cache($type, $data);
}
/**
 * @access private
 */
function elgg_filepath_cache_load($type) {
	return elgg_load_system_cache($type);
}
/**
 * @access private
 */
function elgg_enable_filepath_cache() {
	elgg_enable_system_cache();
}
/**
 * @access private
 */
function elgg_disable_filepath_cache() {
	elgg_disable_system_cache();
}

/* Simplecache */

/**
 * Registers a view to simple cache.
 *
 * Simple cache is a caching mechanism that saves the output of
 * views and its extensions into a file.  If the view is called
 * by the {@link engine/handlers/cache_handler.php} file, Elgg will
 * not be loaded and the contents of the view will returned
 * from file.
 *
 * @warning Simple cached views must take no parameters and return
 * the same content no matter who is logged in.
 *
 * @example
 * 		$blog_js = elgg_get_simplecache_url('js', 'blog/save_draft');
 *		elgg_register_simplecache_view('js/blog/save_draft');
 *		elgg_register_js('elgg.blog', $blog_js);
 *		elgg_load_js('elgg.blog');
 *
 * @param string $viewname View name
 *
 * @return void
 * @link http://docs.elgg.org/Views/Simplecache
 * @see elgg_regenerate_simplecache()
 * @since 1.8.0
 */
function elgg_register_simplecache_view($viewname) {
	elgg_register_external_view($viewname, true);
}

/**
 * Get the URL for the cached file
 * 
 * @warning You must register the view with elgg_register_simplecache_view()	  	
 * for caching to work. See elgg_register_simplecache_view() for a full example.
 *
 * @param string $type The file type: css or js
 * @param string $view The view name after css/ or js/
 * @return string
 * @since 1.8.0
 */
function elgg_get_simplecache_url($type, $view) {
	return elgg_get_external_view_url("$type/$view");
}

/**
 * Returns the type of output expected from the view.
 * 
 * css/* views always return "css"
 * js/* views always return "js"
 * 
 * TODO: view/name.suffix returns "suffix"
 * 
 * Otherwise, returns "unknown"
 * 
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
 * Regenerates the simple cache.
 *
 * @warning This does not invalidate the cache, but actively rebuilds it.
 *
 * @param string $viewtype Optional viewtype to regenerate. Defaults to all valid viewtypes.
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
			if (!elgg_view_exists($view)) {
				continue;
			}
			$content = elgg_view($view);
			$hook_type = _elgg_get_view_filetype($view);
			$hook_params = array(
				'view' => $view,
				'viewtype' => $viewtype,
				'view_content' => $content,
			);
			$content = elgg_trigger_plugin_hook('simplecache:generate', $hook_type, $hook_params, $content);
			
			$view_uid = md5("$viewtype|$view");
			$cache_file = $CONFIG->dataroot . 'views_simplecache/' . $view_uid;

			file_put_contents($cache_file, $content);
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
		_elgg_rmdir("{$CONFIG->dataroot}views_simplecache");
	}
}


/**
 * Recursively deletes a directory, including all hidden files.
 * 
 * @return boolean Whether the dir was successfully deleted.
 * @access private
 */
function _elgg_rmdir($dir) {
	$files = array_diff(scandir($dir_path), array('.', '..'));
	
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

	// reset cache times
	$viewtypes = $CONFIG->view_types;

	if (!is_array($viewtypes)) {
		return false;
	}

	foreach ($viewtypes as $viewtype) {
		$return &= datalist_set("simplecache_lastupdate_$viewtype", 0);
		$return &= datalist_set("simplecache_lastcached_$viewtype", 0);
	}

	return $return;
}

/**
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
 * @access private
 */
function _elgg_cache_init() {
	global $CONFIG;

	$viewtype = elgg_get_viewtype();

	// Regenerate the simple cache if expired.
	// Don't do it on upgrade because upgrade does it itself.
	// @todo - move into function and perhaps run off init system event
	if (!defined('UPGRADING')) {
		$lastupdate = datalist_get("simplecache_lastupdate_$viewtype");
		$lastcached = datalist_get("simplecache_lastcached_$viewtype");
		if ($lastupdate == 0 || $lastcached < $lastupdate) {
			elgg_regenerate_simplecache($viewtype);
			$lastcached = datalist_get("simplecache_lastcached_$viewtype");
		}
		$CONFIG->lastcache = $lastcached;
	}

	// cache system data if enabled and not loaded
	if ($CONFIG->system_cache_enabled && !$CONFIG->system_cache_loaded) {
		elgg_save_system_cache('view_locations', serialize($CONFIG->views->locations));
		elgg_save_system_cache('view_types', serialize($CONFIG->view_types));
	}

	if ($CONFIG->system_cache_enabled && !$CONFIG->i18n_loaded_from_cache) {
		reload_all_translations();
		foreach ($CONFIG->translations as $lang => $map) {
			elgg_save_system_cache("$lang.php", serialize($map));
		}
	}
}

elgg_register_event_handler('ready', 'system', '_elgg_cache_init');
