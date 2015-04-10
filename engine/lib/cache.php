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
 * Returns an \ElggCache object suitable for caching system information
 *
 * @todo Can this be done in a cleaner way?
 * @todo Swap to memcache etc?
 *
 * @return \ElggFileCache
 */
function elgg_get_system_cache() {
	return _elgg_services()->systemCache->getFileCache();
}

/**
 * Reset the system cache by deleting the caches
 *
 * @return void
 */
function elgg_reset_system_cache() {
	_elgg_services()->systemCache->reset();
}

/**
 * Saves a system cache.
 *
 * @param string $type The type or identifier of the cache
 * @param string $data The data to be saved
 * @return bool
 */
function elgg_save_system_cache($type, $data) {
	return _elgg_services()->systemCache->save($type, $data);
}

/**
 * Retrieve the contents of a system cache.
 *
 * @param string $type The type of cache to load
 * @return string
 */
function elgg_load_system_cache($type) {
	return _elgg_services()->systemCache->load($type);
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
	_elgg_services()->systemCache->enable();
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
	_elgg_services()->systemCache->disable();
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
	_elgg_services()->simpleCache->registerView($view_name);
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
	return _elgg_services()->simpleCache->getUrl($type, $view);
}


/**
 * Get the base url for simple cache requests
 * 
 * @return string The simplecache root url for the current viewtype.
 * @access private
 */
function _elgg_get_simplecache_root() {
	return _elgg_services()->simpleCache->getRoot();
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
	return _elgg_services()->simpleCache->isEnabled();
}

/**
 * Enables the simple cache.
 *
 * @see elgg_register_simplecache_view()
 * @return void
 * @since 1.8.0
 */
function elgg_enable_simplecache() {
	_elgg_services()->simpleCache->enable();
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
	_elgg_services()->simpleCache->disable();
}

/**
 * Recursively deletes a directory, including all hidden files.
 * 
 * TODO(ewinslow): Move to filesystem package
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
	_elgg_services()->simpleCache->invalidate();
}

/**
 * Flush all the registered caches
 * 
 * @return void
 * @since 1.11
 */
function elgg_flush_caches() {
	_elgg_services()->events->trigger('cache:flush', 'system');
}

/**
 * Initializes the simplecache lastcache variable and creates system cache files
 * when appropriate.
 * 
 * @access private
 */
function _elgg_cache_init() {
	_elgg_services()->simpleCache->init();
	_elgg_services()->systemCache->init();
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('ready', 'system', '_elgg_cache_init');
	
	// register plugin hooks for cache reset
	$events->registerHandler('cache:flush', 'system', 'elgg_reset_system_cache');
	$events->registerHandler('cache:flush', 'system', 'elgg_invalidate_simplecache');
};
