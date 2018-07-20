<?php

namespace Elgg\Cache;

use Elgg\Application;
use Elgg\Config;
use Elgg\ViewsService;


/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @since  1.10.0
 */
class SimpleCache {

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var ViewsService
	 */
	protected $views;

	/**
	 * Constructor
	 *
	 * @param Config       $config Elgg's global configuration
	 * @param ViewsService $views  Views service
	 */
	public function __construct(
		Config $config,
		ViewsService $views
	) {
		$this->config = $config;
		$this->views = $views;
	}

	/**
	 * Get the URL for the cached view.
	 *
	 * Recommended usage is to just pass the entire view name as the first and only arg:
	 *
	 * ```
	 * $blog_js = $simpleCache->getUrl('blog/save_draft.js');
	 * $favicon = $simpleCache->getUrl('graphics/favicon.ico');
	 * ```
	 *
	 * For backwards compatibility with older versions of Elgg, you can also pass
	 * "js" or "css" as the first arg, with the rest of the view name as the second arg:
	 *
	 * ```
	 * $blog_js = $simpleCache->getUrl('js', 'blog/save_draft.js');
	 * ```
	 *
	 * This automatically registers the view with Elgg's simplecache.
	 *
	 * @param string $view    The full view name
	 * @param string $subview If the first arg is "css" or "js", the rest of the view name
	 *
	 * @return string
	 */
	function getUrl($view, $subview = '') {
		// handle `getUrl('js', 'js/blog/save_draft')`
		if (($view === 'js' || $view === 'css') && 0 === strpos($subview, $view . '/')) {
			$view = $subview;
			$subview = '';
		}

		// handle `getUrl('js', 'blog/save_draft')`
		if (!empty($subview)) {
			$view = "$view/$subview";
		}

		$view = ViewsService::canonicalizeViewName($view);

		// should be normalized to canonical form by now: `getUrl('blog/save_draft.js')`
		$this->views->registerCacheableView($view);

		return $this->getRoot() . $view;
	}

	/**
	 * Get the base url for simple cache requests
	 *
	 * @return string The simplecache root url for the current viewtype.
	 * @access private
	 */
	function getRoot() {
		$viewtype = elgg_get_viewtype();
		if ($this->isEnabled()) {
			$lastcache = (int) $this->config->lastcache;
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
		return (bool) $this->config->simplecache_enabled;
	}

	/**
	 * Enables the simple cache.
	 *
	 * @see elgg_register_simplecache_view()
	 * @return void
	 */
	function enable() {
		$this->config->save('simplecache_enabled', 1);
		$this->invalidate();
	}

	/**
	 * Disables the simple cache.
	 *
	 * @warning Simplecache is also purged when disabled.
	 *
	 * @see     elgg_register_simplecache_view()
	 * @return void
	 */
	function disable() {
		if ($this->config->simplecache_enabled) {
			$this->config->save('simplecache_enabled', 0);

			$this->invalidate();
		}
	}

	/**
	 * Returns the path to where views are simplecached.
	 *
	 * @return string
	 */
	private function getPath() {
		return $this->config->assetroot;
	}

	/**
	 * Deletes all cached views in the simplecache and sets the lastcache and
	 * lastupdate time to 0 for every valid viewtype.
	 *
	 * @return bool
	 */
	function invalidate() {
		_elgg_rmdir($this->getPath(), true);

		$time = time();
		$this->config->save("simplecache_lastupdate", $time);
		$this->config->lastcache = $time;

		return true;
	}
}
