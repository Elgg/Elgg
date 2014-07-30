<?php
namespace Elgg;
/**
 * Manages core autoloading and caching of class maps
 *
 * @access private
 * 
 * @package    Elgg.Core
 * @subpackage Autoloader
 */
class AutoloadManager {

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
	protected $scannedDirs = array();

	/**
	 * @var bool was data in the manager altered?
	 */
	protected $altered = false;

	/**
	 * @var \ElggCache
	 */
	protected $storage = null;

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
		$dir = new \DirectoryIterator($dir);
		$map = array();

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
	 * Register the location of a class on the class map
	 *
	 * @param string $class Class name
	 * @param string $path  Path of class file
	 * @return \Elgg\AutoloadManager
	 */
	public function setClassPath($class, $path) {
		$this->loader->getClassMap()->setPath($class, $path);
		return $this;
	}

	/**
	 * If necessary, save necessary state details
	 *
	 * @return \Elgg\AutoloadManager
	 */
	public function saveCache() {
		if ($this->storage) {
			$map = $this->loader->getClassMap();
			if ($this->altered || $map->getAltered()) {
				$spec[self::KEY_CLASSES] = $map->getMap();
				$spec[self::KEY_SCANNED_DIRS] = $this->scannedDirs;
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
	 * Some method that does something
	 * 
	 * @todo what is a spec?
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
	 * @return \Elgg\AutoloadManager
	 */
	public function deleteCache() {
		if ($this->storage) {
			$this->storage->delete(self::FILENAME);
		}
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

	/**
	 * Set the cache storage object
	 * 
	 * @param \ElggCache $storage Cache object
	 * @return void
	 */
	public function setStorage(\ElggCache $storage) {
		$this->storage = $storage;
	}
}

