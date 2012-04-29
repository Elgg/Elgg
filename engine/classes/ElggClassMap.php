<?php
/**
 * A map of class names to absolute file paths
 *
 * @class      ElggClassMap
 * @package    Elgg.Core
 */
class ElggClassMap {

	/**
	 * @var array
	 */
	protected $map = array();

	/**
	 * @var bool
	 */
	protected $altered = false;

	/**
	 * Get the path for a class/interface/trait
	 *
	 * @param string $class a class/interface/trait name
	 * @return string|null the file path or null if not in map
	 */
	public function getPath($class) {
		if ('\\' == $class[0]) {
			$class = substr($class, 1);
		}
		return isset($this->map[$class]) ? $this->map[$class] : null;
	}

	/**
	 * Set the path for a class/interface/trait, and mark map as altered
	 *
	 * @param string $class a class/interface/trait name
	 * @param string $path absolute file path
	 * @return ElggClassMap
	 */
	public function setPath($class, $path) {
		if ('\\' == $class[0]) {
			$class = substr($class, 1);
		}
		$this->map[$class] = $path;
		$this->altered = true;
		return $this;
	}

	/**
	 * Was this map altered by the class loader?
	 *
	 * @return bool
	 */
	public function getAltered() {
		return $this->altered;
	}

	/**
	 * @param bool $altered
	 * @return ElggClassMap
	 */
	public function setAltered($altered) {
		$this->altered = (bool) $altered;
		return $this;
	}

	/**
	 * Get the full map
	 *
	 * @return array
	 */
	public function getMap() {
		return $this->map;
	}

	/**
	 * Set the full map
	 *
	 * @param array $map array with keys being class/interface/trait names and
	 *                   values the absolute file paths that define them
	 * @return ElggClassMap
	 */
	public function setMap(array $map) {
		$this->map = $map;
		return $this;
	}

	/**
	 * @param array $map
	 * @return ElggClassMap
	 */
	public function mergeMap(array $map) {
		$this->map = array_merge($this->map, $map);
		return $this;
	}
}
