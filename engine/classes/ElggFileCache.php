<?php
/**
 * ElggFileCache
 * Store cached data in a file store.
 *
 * @package Elgg
 * @subpackage API
 */
class ElggFileCache extends ElggCache {
	/**
	 * Set the Elgg cache.
	 *
	 * @param string $cache_path The cache path.
	 * @param int $max_age Maximum age in seconds, 0 if no limit.
	 * @param int $max_size Maximum size of cache in seconds, 0 if no limit.
	 */
	function __construct($cache_path, $max_age = 0, $max_size = 0) {
		$this->set_variable("cache_path", $cache_path);
		$this->set_variable("max_age", $max_age);
		$this->set_variable("max_size", $max_size);

		if ($cache_path=="") {
			throw new ConfigurationException(elgg_echo('ConfigurationException:NoCachePath'));
		}
	}

	/**
	 * Create and return a handle to a file.
	 *
	 * @param string $filename
	 * @param string $rw
	 */
	protected function create_file($filename, $rw = "rb") {
		// Create a filename matrix
		$matrix = "";
		$depth = strlen($filename);
		if ($depth > 5) {
			$depth = 5;
		}

		// Create full path
		$path = $this->get_variable("cache_path") . $matrix;
		if (!is_dir($path)) {
			mkdir($path, 0700, true);
		}

		// Open the file
		if ((!file_exists($path . $filename)) && ($rw=="rb")) {
			return false;
		}

		return fopen($path . $filename, $rw);
	}

	/**
	 * Create a sanitised filename for the file.
	 *
	 * @param string $filename
	 */
	protected function sanitise_filename($filename) {
		// @todo : Writeme

		return $filename;
	}

	/**
	 * Save a key
	 *
	 * @param string $key
	 * @param string $data
	 * @return boolean
	 */
	public function save($key, $data) {
		$f = $this->create_file($this->sanitise_filename($key), "wb");
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
	 * @param string $key
	 * @param int $offset
	 * @param int $limit
	 * @return string
	 */
	public function load($key, $offset = 0, $limit = null) {
		$f = $this->create_file($this->sanitise_filename($key));
		if ($f) {
			//fseek($f, $offset);
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
	 * @param string $key
	 * @return bool
	 */
	public function delete($key) {
		$dir = $this->get_variable("cache_path");
		
		if (file_exists($dir.$key)) {
			return unlink($dir.$key);
		}
		return TRUE;
	}

	public function clear() {
		// @todo writeme
	}

	public function __destruct() {
		// @todo Check size and age, clean up accordingly
		$size = 0;
		$dir = $this->get_variable("cache_path");

		// Short circuit if both size and age are unlimited
		if (($this->get_variable("max_age")==0) && ($this->get_variable("max_size")==0)) {
			return;
		}

		$exclude = array(".","..");

		$files = scandir($dir);
		if (!$files) {
			throw new IOException(sprintf(elgg_echo('IOException:NotDirectory'), $dir));
		}

		// Perform cleanup
		foreach ($files as $f) {
			if (!in_array($f, $exclude)) {
				$stat = stat($dir.$f);

				// Add size
				$size .= $stat['size'];

				// Is this older than my maximum date?
				if (($this->get_variable("max_age")>0) && (time() - $stat['mtime'] > $this->get_variable("max_age"))) {
					unlink($dir.$f);
				}

				// @todo Size
			}
		}
	}
}