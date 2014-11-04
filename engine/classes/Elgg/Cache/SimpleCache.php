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
class SimpleCache {

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
	 */
	function registerView($view_name) {
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
	 */
	function getUrl($type, $view) {
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
	function getRoot() {
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
	 * Is simple cache enabled
	 *
	 * @return bool
	 */
	function isEnabled() {
		return (bool) elgg_get_config('simplecache_enabled');
	}
	
	/**
	 * Enables the simple cache.
	 *
	 * @see elgg_register_simplecache_view()
	 * @return void
	 */
	function enable() {
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
	 */
	function disable() {
		if (elgg_get_config('simplecache_enabled')) {
			datalist_set('simplecache_enabled', 0);
			elgg_set_config('simplecache_enabled', 0);
	
			// purge simple cache
			_elgg_rmdir(elgg_get_data_path() . "views_simplecache");
		}
	}
	
	/**
	 * Deletes all cached views in the simplecache and sets the lastcache and
	 * lastupdate time to 0 for every valid viewtype.
	 *
	 * @return bool
	 */
	function invalidate() {
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
	 * Set up $CONFIG appropriately on engine boot.
	 *
	 * @return void
	 */
	function init() {
		global $CONFIG;

		if (!defined('UPGRADING') && empty($CONFIG->lastcache)) {
			$CONFIG->lastcache = (int)datalist_get('simplecache_lastupdate');
		}
	}	
}