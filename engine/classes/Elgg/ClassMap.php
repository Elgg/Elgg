<?php
namespace Elgg;
/**
 * A map of class names to absolute file paths
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Autoloader
 */
class ClassMap {

	/**
	 * @var array
	 */
	protected $map = [];

	/**
	 * @var bool
	 */
	protected $altered = false;

	/**
	 * Get the path for a class/interface/trait
	 *
	 * @param string $class a class/interface/trait name
	 * @return string the file path or empty string
	 */
	public function getPath($class) {
		if ('\\' === $class[0]) {
			$class = substr($class, 1);
		}
		return isset($this->map[$class]) ? $this->map[$class] : "";
	}

	/**
	 * Set the path for a class/interface/trait, and mark map as altered
	 *
	 * @param string $class a class/interface/trait name
	 * @param string $path  absolute file path
	 * @return \Elgg\ClassMap
	 */
	public function setPath($class, $path) {
		if ('\\' === $class[0]) {
			$class = substr($class, 1);
		}
		if (!isset($this->map[$class]) || $this->map[$class] !== $path) {
			$this->map[$class] = $path;
			$this->altered = true;
		}
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
	 * Set the altered flag
	 *
	 * @param bool $altered Whether the class map has been altered
	 * @return \Elgg\ClassMap
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
	 * @return \Elgg\ClassMap
	 */
	public function setMap(array $map) {
		$this->map = $map;
		return $this;
	}

	/**
	 * Merge a class map with the current map
	 *
	 * @param array $map array with keys being class/interface/trait names and
	 *                   values the absolute file paths that define them
	 * @return \Elgg\ClassMap
	 */
	public function mergeMap(array $map) {
		$this->map = array_merge($this->map, $map);
		return $this;
	}
}

