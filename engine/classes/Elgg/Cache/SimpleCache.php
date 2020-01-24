<?php

namespace Elgg\Cache;

use Elgg\Config;
use Elgg\ViewsService;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @internal
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
	public function getUrl($view, $subview = '') {
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
	 * @return string The simplecache root url for the current viewtype
	 */
	public function getRoot() {
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
	public function isEnabled() {
		return (bool) $this->config->simplecache_enabled;
	}

	/**
	 * Enables the simple cache.
	 *
	 * @see elgg_register_simplecache_view()
	 * @return void
	 */
	public function enable() {
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
	public function disable() {
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
		return (string) $this->config->assetroot;
	}

	/**
	 * Deletes all cached views in the simplecache
	 *
	 * @return true
	 */
	public function invalidate() {
		// Simplecache doesn't have invalidation as an action.
		// This is handled by generating new urls
		return true;
	}
	
	/**
	 * Deletes all cached views in the simplecache
	 *
	 * @return bool
	 * @since 3.3
	 */
	public function clear() {
		elgg_delete_directory($this->getPath(), true);
		
		return true;
	}
	
	/**
	 * Purge old/stale cache content
	 *
	 * @return void
	 */
	public function purge() {
		$lastcache = (int) $this->config->lastcache;
		
		if (!is_dir($this->getPath())) {
			return;
		}
		
		$di = new \DirectoryIterator($this->getPath());
		
		/* @var $file_info \DirectoryIterator */
		foreach ($di as $file_info) {
			if (!$file_info->isDir() || $file_info->isDot()) {
				continue;
			}
			
			if ((int) $file_info->getBasename() === $lastcache) {
				continue;
			}
			
			elgg_delete_directory($file_info->getPathname());
		}
	}
}
