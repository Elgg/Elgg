<?php
/**
 * A map of class names to absolute file paths
 *
 * @class      ElggClassMap
 * @package    Elgg.Core
 */
class ElggClassMap
{
	protected $map = array();
	protected $altered = false;

	/**
	 * @param array $map array with keys being class/interface/trait names and
	 *                   values the absolute file paths that define them
	 */
	public function __construct(array $map = array())
	{
		$this->map = $map;
	}

	/**
	 * Get the path for a class/interface/trait
	 *
	 * @param string $class a class/interface/trait name
	 * @return string|null the file path or null if not in map
	 */
	public function getPath($class)
	{
		if ('\\' == $class[0]) {
			$class = substr($class, 1);
		}
		return isset($this->map[$class]) ? $this->map[$class] : null;
	}

	/**
	 * Set the path for a class/interface/trait
	 *
	 * @param string $class a class/interface/trait name
	 * @param string $path absolute file path
	 */
	public function setPath($class, $path)
	{
		if ('\\' == $class[0]) {
			$class = substr($class, 1);
		}
		$this->map[$class] = $path;
		$this->altered = true;
	}

	/**
	 * Was this map altered since its construction?
	 *
	 * @return bool
	 */
	public function getAltered()
	{
		return $this->altered;
	}

	/**
	 * Get the full map
	 *
	 * @return array
	 */
	public function getMap()
	{
		return $this->map;
	}
}
