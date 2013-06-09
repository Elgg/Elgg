<?php
/**
 * ElggFileCache
 * Store cached data in a file store.
 *
 * @package    Elgg.Core
 * @subpackage Caches
 */
class ElggFileCache extends ElggCache {
	/**
	 * Set the Elgg cache.
	 *
	 * @param string $cache_path The cache path.
	 * @param int    $max_age    Maximum age in seconds, 0 if no limit.
	 * @param int    $max_size   Maximum size of cache in seconds, 0 if no limit.
	 *
	 * @throws ConfigurationException
	 */
	public function __construct($cache_path, $max_age = 0, $max_size = 0) {
		$this->setVariable("cache_path", $cache_path);
		$this->setVariable("max_age", $max_age);
		$this->setVariable("max_size", $max_size);

		if ($cache_path == "") {
			throw new ConfigurationException("Cache path set to nothing!");
		}
	}

	// @codingStandardsIgnoreStart
	/**
	 * Create and return a handle to a file.
	 *
	 * @deprecated 1.8 Use ElggFileCache::createFile()
	 *
	 * @param string $filename Filename to save as
	 * @param string $rw       Write mode
	 *
	 * @return mixed
	 */
	protected function create_file($filename, $rw = "rb") {
		elgg_deprecated_notice('ElggFileCache::create_file() is deprecated by ::createFile()', 1.8);

		return $this->createFile($filename, $rw);
	}
	// @codingStandardsIgnoreEnd

	/**
	 * Create and return a handle to a file.
	 *
	 * @param string $filename Filename to save as
	 * @param string $rw       Write mode
	 *
	 * @return mixed
	 */
	protected function createFile($filename, $rw = "rb") {
		// Create a filename matrix
		$matrix = "";
		$depth = strlen($filename);
		if ($depth > 5) {
			$depth = 5;
		}

		// Create full path
		$path = $this->getVariable("cache_path") . $matrix;
		if (!is_dir($path)) {
			mkdir($path, 0700, true);
		}

		// Open the file
		if ((!file_exists($path . $filename)) && ($rw == "rb")) {
			return false;
		}

		return fopen($path . $filename, $rw);
	}

	// @codingStandardsIgnoreStart
	/**
	 * Create a sanitised filename for the file.
	 *
	 * @deprecated 1.8 Use ElggFileCache::sanitizeFilename()
	 *
	 * @param string $filename The filename
	 *
	 * @return string
	 */
	protected function sanitise_filename($filename) {
		// @todo : Writeme

		return $filename;
	}
	// @codingStandardsIgnoreEnd

	/**
	 * Create a sanitised filename for the file.
	 *
	 * @param string $filename The filename
	 *
	 * @return string
	 */
	protected function sanitizeFilename($filename) {
		// @todo : Writeme

		return $filename;
	}

	/**
	 * Save a key
	 *
	 * @param string $key  Name
	 * @param string $data Value
	 *
	 * @return boolean
	 */
	public function save($key, $data) {
		$f = $this->createFile($this->sanitizeFilename($key), "wb");
		if ($f) {
			$result = fwrite($f, $data);
			fclose($f);

			return $result;
		}

		return false;
	}

	/**
	 * Load a key
	 *
	 * @param string $key    Name
	 * @param int    $offset Offset
	 * @param int    $limit  Limit
	 *
	 * @return string
	 */
	public function load($key, $offset = 0, $limit = null) {
		$f = $this->createFile($this->sanitizeFilename($key));
		if ($f) {
			if (!$limit) {
				$limit = -1;
			}

			$data = stream_get_contents($f, $limit, $offset);

			fclose($f);

			return $data;
		}

		return false;
	}

	/**
	 * Invalidate a given key.
	 *
	 * @param string $key Name
	 *
	 * @return bool
	 */
	public function delete($key) {
		$dir = $this->getVariable("cache_path");

		if (file_exists($dir . $key)) {
			return unlink($dir . $key);
		}
		return true;
	}

	/**
	 * Delete all files in the directory of this file cache
	 *
	 * @return void
	 */
	public function clear() {
		$dir = $this->getVariable("cache_path");

		$exclude = array(".", "..");

		$files = scandir($dir);
		if (!$files) {
			return;
		}

		foreach ($files as $f) {
			if (!in_array($f, $exclude)) {
				unlink($dir . $f);
			}
		}
	}

	/**
	 * Preform cleanup and invalidates cache upon object destruction
	 *
	 * @throws IOException
	 */
	public function __destruct() {
		// @todo Check size and age, clean up accordingly
		$size = 0;
		$dir = $this->getVariable("cache_path");

		// Short circuit if both size and age are unlimited
		if (($this->getVariable("max_age") == 0) && ($this->getVariable("max_size") == 0)) {
			return;
		}

		$exclude = array(".", "..");

		$files = scandir($dir);
		if (!$files) {
			throw new IOException($dir . " is not a directory.");
		}

		// Perform cleanup
		foreach ($files as $f) {
			if (!in_array($f, $exclude)) {
				$stat = stat($dir . $f);

				// Add size
				$size .= $stat['size'];

				// Is this older than my maximum date?
				if (($this->getVariable("max_age") > 0) && (time() - $stat['mtime'] > $this->getVariable("max_age"))) {
					unlink($dir . $f);
				}

				// @todo Size
			}
		}
	}
}
