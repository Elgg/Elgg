<?php
/**
 * ElggStaticVariableCache
 * Dummy cache which stores values in a static array. Using this makes future replacements to other caching back
 * ends (eg memcache) much easier.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage API
 */
class ElggStaticVariableCache extends ElggSharedMemoryCache {
	/**
	 * The cache.
	 *
	 * @var unknown_type
	 */
	private static $__cache;

	/**
	 * Create the variable cache.
	 *
	 * This function creates a variable cache in a static variable in memory, optionally with a given namespace (to avoid overlap).
	 *
	 * @param string $namespace The namespace for this cache to write to - note, namespaces of the same name are shared!
	 */
	function __construct($namespace = 'default') {
		$this->setNamespace($namespace);
		$this->clear();
	}

	public function save($key, $data) {
		$namespace = $this->getNamespace();

		ElggStaticVariableCache::$__cache[$namespace][$key] = $data;

		return true;
	}

	public function load($key, $offset = 0, $limit = null) {
		$namespace = $this->getNamespace();

		if (isset(ElggStaticVariableCache::$__cache[$namespace][$key])) {
			return ElggStaticVariableCache::$__cache[$namespace][$key];
		}

		return false;
	}

	public function delete($key) {
		$namespace = $this->getNamespace();

		unset(ElggStaticVariableCache::$__cache[$namespace][$key]);

		return true;
	}

	public function clear() {
		$namespace = $this->getNamespace();

		if (!isset(ElggStaticVariableCache::$__cache)) {
			ElggStaticVariableCache::$__cache = array();
		}

		ElggStaticVariableCache::$__cache[$namespace] = array();
	}
}