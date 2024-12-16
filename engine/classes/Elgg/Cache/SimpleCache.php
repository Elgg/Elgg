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
	 * @var array Simplecache views (view names are keys)
	 *
	 * [view] = true
	 */
	protected array $simplecache_views = [];

	/**
	 * Constructor
	 *
	 * @param Config       $config Elgg's global configuration
	 * @param ViewsService $views  Views service
	 */
	public function __construct(
		protected Config $config,
		protected ViewsService $views
	) {
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
		$this->registerCacheableView($view);

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
	 * Register a view as cacheable
	 *
	 * @param string $view the view name
	 *
	 * @return void
	 */
	public function registerCacheableView(string $view): void {
		$this->simplecache_views[$view] = true;
	}
	
	/**
	 * Is the view cacheable
	 *
	 * @param string $view the view name
	 *
	 * @return bool
	 */
	public function isCacheableView(string $view): bool {
		if (isset($this->simplecache_views[$view])) {
			return true;
		}
		
		// build list of viewtypes to check
		$current_viewtype = $this->views->getViewtype();
		$viewtypes = [$current_viewtype];
		
		if ($this->views->doesViewtypeFallback($current_viewtype) && $current_viewtype != 'default') {
			$viewtypes[] = 'default';
		}
		
		// If a static view file is found in any viewtype, it's considered cacheable
		foreach ($viewtypes as $viewtype) {
			$file = $this->views->findViewFile($view, $viewtype);
			
			if ($file && pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
				$this->simplecache_views[$view] = true;
				
				return true;
			}
		}
		
		// Assume not-cacheable by default
		return false;
	}
	
	/**
	 * Returns the cacheable views
	 *
	 * @return array
	 */
	public function getCacheableViews(): array {
		return $this->simplecache_views;
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
	 */
	public function enable(): void {
		$this->config->save('simplecache_enabled', 1);
	}

	/**
	 * Disables the simple cache.
	 *
	 * @return void
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
	 * @param string   $viewtype   view type
	 * @param string   $view       cached view
	 * @param null|int $cache_time (optional) cache time (default \Elgg\Config->lastcache;
	 *
	 * @return string
	 */
	protected function getCacheFilename(string $viewtype, string $view, ?int $cache_time = null): string {
		if (!isset($cache_time)) {
			$cache_time = $this->config->lastcache;
		}
		
		$filename = $this->getPath() . "{$cache_time}/{$viewtype}/{$view}";
		return Paths::sanitize($filename, false);
	}
	
	/**
	 * Checks if /cache directory has been symlinked to views simplecache directory
	 *
	 * @return bool
	 * @since 6.1
	 */
	public function isSymbolicLinked(): bool {
		$simplecache_path = rtrim($this->getPath(), '/');
		$symlink_path = elgg_get_root_path() . 'cache';
		
		return is_dir($symlink_path) && realpath($simplecache_path) === realpath($symlink_path);
	}
	
	/**
	 * Symlinks /cache directory to views simplecache directory
	 *
	 * @return bool
	 * @since 6.1
	 */
	public function createSymbolicLink(): bool {
		if ($this->isSymbolicLinked()) {
			// symlink exists, no need to proceed
			return true;
		}
		
		$symlink_path = Paths::project() . 'cache';
		if (is_dir($symlink_path)) {
			// Cache directory already exists
			// We can not proceed without overwriting files
			return false;
		}
		
		$simplecache_path = rtrim($this->getPath(), '/');
		if (!is_dir($simplecache_path)) {
			// Views simplecache directory has not yet been created
			mkdir($simplecache_path, 0755, true);
		}
		
		symlink($simplecache_path, $symlink_path);
		
		if ($this->isSymbolicLinked()) {
			return true;
		}
		
		if (is_dir($symlink_path)) {
			unlink($symlink_path);
		}
		
		return false;
	}
}
