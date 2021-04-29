<?php

namespace Elgg;

use Elgg\Traits\Cacheable;

/**
 * Manages core autoloading and caching of class maps
 *
 * @internal
 */
class AutoloadManager {

	use Cacheable;

	const FILENAME = 'autoload_data.php';
	const KEY_CLASSES = 'classes';
	const KEY_SCANNED_DIRS = 'scannedDirs';

	/**
	 * @var \Elgg\ClassLoader
	 */
	protected $loader;

	/**
	 * @var array directories that have already been scanned for classes
	 */
	protected $scannedDirs = [];

	/**
	 * @var bool was data in the manager altered?
	 */
	protected $altered = false;

	/**
	 * Constructor
	 *
	 * @param \Elgg\ClassLoader $loader Class loader object
	 */
	public function __construct(\Elgg\ClassLoader $loader) {
		$this->loader = $loader;
	}

	/**
	 * Add classes found in this directory to the class map and allow classes in
	 * subdirectories to be found by PSR-0 rules.
	 *
	 * We keep track of which dirs were scanned on previous requests so we don't need to
	 * rescan unless the cache is emptied.
	 *
	 * @param string $dir Directory of classes
	 * @return \Elgg\AutoloadManager
	 */
	public function addClasses($dir) {
		if (!in_array($dir, $this->scannedDirs)) {
			$map = $this->loader->getClassMap();
			$map->mergeMap($this->scanClassesDir($dir));
			$this->scannedDirs[] = $dir;
			$this->altered = true;
		}
		$this->loader->addFallback($dir);
		return $this;
	}

	/**
	 * Scan (non-recursively) a /classes directory for PHP files to map directly to classes.
	 *
	 * For BC with Elgg 1.8's autoloader we map these files directly, but besides this
	 * the autoloader is PSR-0 compatible.
	 *
	 * @param string $dir Directory of classes
	 * @return array
	 */
	protected function scanClassesDir($dir) {
		if (!is_dir($dir)) {
			return [];
		}
		
		$dir = new \DirectoryIterator($dir);
		$map = [];

		foreach ($dir as $file) {
			/* @var \SplFileInfo $file */
			if (!$file->isFile() || !$file->isReadable()) {
				continue;
			}

			$path = $file->getRealPath();

			if (pathinfo($path, PATHINFO_EXTENSION) !== 'php') {
				continue;
			}

			$class = $file->getBasename('.php');
			$map[$class] = $path;
		}
		return $map;
	}

	/**
	 * If necessary, save necessary state details
	 *
	 * @return \Elgg\AutoloadManager
	 */
	public function saveCache() {
		if (!$this->cache) {
			return $this;
		}
		
		$map = $this->loader->getClassMap();
		
		if ($this->altered || $map->getAltered()) {
			$classes = $map->getMap();
			$scanned_dirs = $this->scannedDirs;
			
			if (empty($classes) && empty($scanned_dirs)) {
				// if there is nothing to cache do not save it
				return $this;
			}
			
			$this->cache->save(self::FILENAME, [
				self::KEY_CLASSES => $classes,
				self::KEY_SCANNED_DIRS => $scanned_dirs,
			]);
		}
		
		return $this;
	}

	/**
	 * Set the state of the manager from the cache
	 *
	 * @return bool was the cache loaded?
	 */
	public function loadCache() {
		$cache = $this->getCacheFileContents();
		if ($cache) {
			// the cached class map will have the full scanned core classes, so
			// don't consider the earlier mappings as "altering" the map
			$this->loader->getClassMap()
				->setMap($cache[self::KEY_CLASSES])
				->setAltered(false);
			$this->scannedDirs = $cache[self::KEY_SCANNED_DIRS];
			return true;
		}
		$this->altered = true;
		return false;
	}

	/**
	 * Tries to read the contents of the cache file and if valid returns the content
	 *
	 * @return false|array
	 */
	protected function getCacheFileContents() {
		if (!$this->cache) {
			return false;
		}
		
		$spec = $this->cache->load(self::FILENAME);
		if (isset($spec[self::KEY_CLASSES])) {
			return $spec;
		}
		
		return false;
	}

	/**
	 * Delete the cache file
	 *
	 * @return \Elgg\AutoloadManager
	 */
	public function deleteCache() {
		if ($this->cache) {
			$this->cache->delete(self::FILENAME);
		}
		
		$this->loader->getClassMap()->setMap([])->setAltered(true);
		$this->scannedDirs = [];
		$this->altered = true;

		return $this;
	}

	/**
	 * Get the class loader
	 *
	 * @return \Elgg\ClassLoader
	 */
	public function getLoader() {
		return $this->loader;
	}
}
