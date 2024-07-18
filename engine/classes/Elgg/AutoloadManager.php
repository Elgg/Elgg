<?php

namespace Elgg;

use Elgg\Cache\AutoloadCache;

/**
 * Manages core autoloading and caching of class maps
 *
 * @internal
 */
class AutoloadManager {

	const FILENAME = 'autoload_data.php';
	const KEY_CLASSES = 'classes';
	const KEY_SCANNED_DIRS = 'scannedDirs';

	/**
	 * @var array directories that have already been scanned for classes
	 */
	protected array $scannedDirs = [];

	/**
	 * @var bool was data in the manager altered?
	 */
	protected bool $altered = false;

	/**
	 * Constructor
	 *
	 * @param ClassLoader   $loader Class loader object
	 * @param AutoloadCache $cache  Autoload cache
	 */
	public function __construct(protected ClassLoader $loader, protected AutoloadCache $cache) {
		$this->loadCache();
	}

	/**
	 * Add classes found in this directory to the class map and allow classes in
	 * subdirectories to be found by PSR-0 rules.
	 *
	 * We keep track of which dirs were scanned on previous requests so we don't need to
	 * rescan unless the cache is emptied.
	 *
	 * @param string $dir Directory of classes
	 *
	 * @return AutoloadManager
	 */
	public function addClasses(string $dir): static {
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
	 *
	 * @return array
	 */
	protected function scanClassesDir(string $dir): array {
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
	 * @return AutoloadManager
	 */
	public function saveCache(): static {
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
	public function loadCache(): bool {
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
	 * @return null|array
	 */
	protected function getCacheFileContents(): ?array {
		$spec = $this->cache->load(self::FILENAME);
		
		return isset($spec[self::KEY_CLASSES]) ? $spec : null;
	}

	/**
	 * Delete the cache file
	 *
	 * @return AutoloadManager
	 */
	public function deleteCache(): static {
		$this->cache->delete(self::FILENAME);
		$this->loader->getClassMap()->setMap([])->setAltered(true);
		$this->scannedDirs = [];
		$this->altered = true;

		return $this;
	}
}
