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

	protected $loader;

	protected $scannedDirs = array();

	/**
	 * @var bool was data in the manager altered?
	 */
	protected $altered = false;

	/**
	 * @var string location of the cache file
	 */
	protected $cacheFile = '';

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
	 * If necessary, save necessary state details
	 *
	 * @return ElggAutoloadManager
	 */
	public function saveCache() {
		// only save if filename available, and saving necessary
		if ($this->cacheFile) {
			$map = $this->loader->getClassMap();
			if ($this->altered || $map->getAltered()) {
				$spec = array(
					self::KEY_CLASSES => $map->getMap(),
					self::KEY_SCANNED_DIRS => $this->scannedDirs,
				);
				file_put_contents($this->cacheFile, sprintf('<?php return %s;', var_export($spec, true)));
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
		if ($this->cacheFile && file_exists($this->cacheFile)) {
			$spec = (include $this->cacheFile);
			$this->loader->getClassMap()->mergeMap($spec[self::KEY_CLASSES]);
			$this->scannedDirs = $spec[self::KEY_SCANNED_DIRS];
			return true;
		} else {
			$this->altered = true;
			return false;
		}
	}

	/**
	 * Delete the cache file
	 *
	 * @return ElggAutoloadManager
	 */
	public function deleteCache() {
		if ($this->cacheFile && file_exists($this->cacheFile)) {
			unlink($this->cacheFile);
		}
		return $this;
	}

	/**
	 * Set data path so manager knows where to save file
	 *
	 * @param string $dataPath
	 * @return ElggAutoloadManager
	 */
	public function setDataPath($dataPath) {
		$this->cacheFile = rtrim($dataPath, '/\\') . "/" . self::FILENAME;
		return $this;
	}

	/**
	 * @return ElggClassLoader
	 */
	public function getLoader() {
		return $this->loader;
	}
}
