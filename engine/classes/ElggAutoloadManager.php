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
	 * @param $dir
	 */
	public function addClasses($dir) {
		if (! in_array($dir, $this->scannedDirs)) {
			$map = $this->loader->getClassMap();
			$map->mergeMap(ElggClassScanner::createMap($dir));
			$this->scannedDirs[] = $dir;
			$this->altered = true;
		}
	}

	public function addClass($class, $file) {
		$this->loader->getClassMap()->setPath($class, $file);
	}

	/**
	 * If necessary, save necessary state details
	 *
	 * @return array
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
	}

	/**
	 * Set the state of the manager from the cache
	 */
	public function loadCache() {
		if ($this->cacheFile && file_exists($this->cacheFile)) {
			$spec = (include $this->cacheFile);
			$this->loader->getClassMap()->mergeMap($spec[self::KEY_CLASSES]);
			$this->scannedDirs = $spec[self::KEY_SCANNED_DIRS];
		} else {
			$this->altered = true;
		}
	}

	/**
	 * Delete the cache file
	 */
	public function deleteCache() {
		if ($this->cacheFile && file_exists($this->cacheFile)) {
			unlink($this->cacheFile);
		}
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
}
