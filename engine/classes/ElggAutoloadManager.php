<?php
/**
 * Manages core autoloading and caching of class maps
 *
 * @class      ElggAutoloadManager
 * @package    Elgg.Core
 */
class ElggAutoloadManager {

	const FILENAME = 'autoload_data.php';
	const KEY_CLASSES = 'classes';
	const KEY_SCANNED_DIRS = 'scannedDirs';

	/**
	 * @var ElggClassLoader
	 */
	protected $loader;

	/**
	 * @var array directories that have already been scanned for classes
	 */
	protected $scannedDirs = array();

	/**
	 * @var bool was data in the manager altered?
	 */
	protected $altered = false;

	/**
	 * @var ElggCache
	 */
	protected $storage = null;

	/**
	 * @param ElggClassLoader $loader
	 */
	public function __construct(ElggClassLoader $loader) {
		$this->loader = $loader;
	}

	/**
	 * Add classes found in this directory to the class map
	 *
	 * We keep track of which dirs were scanned on previous requests so we don't need to
	 * rescan unless the cache is emptied.
	 *
	 * @param string $dir
	 *
	 * @return ElggAutoloadManager
	 */
	public function addClasses($dir) {
		if (! in_array($dir, $this->scannedDirs)) {
			$map = $this->loader->getClassMap();
			$map->mergeMap(ElggClassScanner::createMap($dir));
			$this->scannedDirs[] = $dir;
			$this->altered = true;
		}
		return $this;
	}

	/**
	 * Register the location of a class on the class map
	 *
	 * @param string $class
	 * @param string $path
	 * @return ElggAutoloadManager
	 */
	public function setClassPath($class, $path) {
		$this->loader->getClassMap()->setPath($class, $path);
		return $this;
	}

	/**
	 * If necessary, save necessary state details
	 *
	 * @return ElggAutoloadManager
	 */
	public function saveCache() {
		if ($this->storage) {
			$map = $this->loader->getClassMap();
			if ($this->altered || $map->getAltered()) {
				$spec = array(
					self::KEY_CLASSES => $map->getMap(),
					self::KEY_SCANNED_DIRS => $this->scannedDirs,
				);
				$this->storage->save(self::FILENAME, serialize($spec));
			}
		}
		return $this;
	}

	/**
	 * Set the state of the manager from the cache
	 *
	 * @return bool was the cache loaded?
	 */
	public function loadCache() {
		$spec = $this->getSpec();
		if ($spec) {
			// the cached class map will have the full scanned core classes, so
			// don't consider the earlier mappings as "altering" the map
			$this->loader->getClassMap()
				->setMap($spec[self::KEY_CLASSES])
				->setAltered(false);
			$this->scannedDirs = $spec[self::KEY_SCANNED_DIRS];
			return true;
		}
		$this->altered = true;
		return false;
	}

	/**
	 * @return bool|array
	 */
	protected function getSpec() {
		if ($this->storage) {
			$serialization = $this->storage->load(self::FILENAME);
			if ($serialization) {
				$spec = unserialize($serialization);
				if (isset($spec[self::KEY_CLASSES])) {
					return $spec;
				}
			}
		}
		return false;
	}

	/**
	 * Delete the cache file
	 *
	 * @return ElggAutoloadManager
	 */
	public function deleteCache() {
		if ($this->storage) {
			$this->storage->delete(self::FILENAME);
		}
		return $this;
	}

	/**
	 * @return ElggClassLoader
	 */
	public function getLoader() {
		return $this->loader;
	}

	/**
	 * @param ElggCache $storage
	 */
	public function setStorage(ElggCache $storage) {
		$this->storage = $storage;
	}
}
