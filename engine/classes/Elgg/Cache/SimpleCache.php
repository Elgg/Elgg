<?php

namespace Elgg\Cache;

use Elgg\Config;
use Elgg\Project\Paths;
use Elgg\ViewsService;

/**
 * Simple cache service
 *
 * @internal
 * @since 1.10.0
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
	 * ```
	 * $blog_js = $simpleCache->getUrl('blog/save_draft.js');
	 * $favicon = $simpleCache->getUrl('graphics/favicon.ico');
	 * ```
	 *
	 * This automatically registers the view with Elgg's simplecache.
	 *
	 * @param string $view The full view name
	 *
	 * @return string
	 */
	public function getUrl(string $view): string {
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
	public function getRoot(): string {
		$viewtype = $this->views->getViewtype();
		if ($this->isEnabled()) {
			$lastcache = (int) $this->config->lastcache;
		} else {
			$lastcache = 0;
		}

		return elgg_normalize_url("/cache/{$lastcache}/{$viewtype}/");
	}

	/**
	 * Is simple cache enabled
	 *
	 * @return bool
	 */
	public function isEnabled(): bool {
		return (bool) $this->config->simplecache_enabled;
	}

	/**
	 * Enables the simple cache.
	 *
	 * @return void
	 * @see elgg_register_simplecache_view()
	 */
	public function enable(): void {
		$this->config->save('simplecache_enabled', 1);
	}

	/**
	 * Disables the simple cache.
	 *
	 * @return void
	 * @see elgg_register_simplecache_view()
	 */
	public function disable(): void {
		if (!$this->isEnabled()) {
			return;
		}
		
		$this->config->save('simplecache_enabled', 0);
	}

	/**
	 * Returns the path to where views are simplecached.
	 *
	 * @return string
	 */
	protected function getPath(): string {
		return (string) $this->config->assetroot;
	}
	
	/**
	 * Deletes all cached views in the simplecache
	 *
	 * @return void
	 *
	 * @since 3.3
	 */
	public function clear(): void {
		elgg_delete_directory($this->getPath(), true);
	}
	
	/**
	 * Purge old/stale cache content
	 *
	 * @return void
	 */
	public function purge(): void {
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
	
	/**
	 * Check if a asset exists in the cache
	 *
	 * @param int    $cache_time time the asset was cached
	 * @param string $viewtype   view type
	 * @param string $view       cached view name
	 *
	 * @return bool
	 * @since 4.1
	 */
	public function cachedAssetExists(int $cache_time, string $viewtype, string $view): bool {
		$filename = $this->getCacheFilename($viewtype, $view, $cache_time);
		
		return file_exists($filename);
	}
	
	/**
	 * Get the cache location of an existing cached asset
	 *
	 * @param int    $cache_time time the asset was cached
	 * @param string $viewtype   view type
	 * @param string $view       cached view name
	 *
	 * @return string|null null if asset doesn't exist
	 * @since 4.1
	 */
	public function getCachedAssetLocation(int $cache_time, string $viewtype, string $view): ?string {
		if (!$this->cachedAssetExists($cache_time, $viewtype, $view)) {
			return null;
		}
		
		return $this->getCacheFilename($viewtype, $view, $cache_time);
	}
	
	/**
	 * Store an asset for caching
	 *
	 * @param string $viewtype view type
	 * @param string $view     view to cache
	 * @param string $contents view contents
	 *
	 * @return int
	 */
	public function cacheAsset(string $viewtype, string $view, string $contents): int {
		$filename = $this->getCacheFilename($viewtype, $view);
		$dir = dirname($filename);
		
		if (!is_dir($dir)) {
			// PHP and the server accessing the cache symlink may be a different user. And here
			// it's safe to make everything readable anyway.
			mkdir($dir, 0775, true);
		}
		
		$result = file_put_contents($filename, $contents);
		chmod($filename, 0664);
		
		return $result;
	}
	
	/**
	 * Get the cache file location
	 *
	 * @param string $viewtype   view type
	 * @param string $view       cached view
	 * @param int    $cache_time (optional) cache time (default \Elgg\Config->lastcache;
	 *
	 * @return string
	 */
	protected function getCacheFilename(string $viewtype, string $view, int $cache_time = null): string {
		if (!isset($cache_time)) {
			$cache_time = $this->config->lastcache;
		}
		
		$filename = $this->getPath() . "{$cache_time}/{$viewtype}/{$view}";
		return Paths::sanitize($filename, false);
	}
}
