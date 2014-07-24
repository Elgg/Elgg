<?php
/**
 * \ElggStaticVariableCache
 * Dummy cache which stores values in a static array. Using this makes future
 * replacements to other caching back ends (eg memcache) much easier.
 *
 * @package    Elgg.Core
 * @subpackage Cache
 */
class ElggStaticVariableCache extends \ElggSharedMemoryCache {
	/**
	 * The cache.
	 *
	 * @var array
	 */
	private static $__cache;

	/**
	 * Create the variable cache.
	 *
	 * This function creates a variable cache in a static variable in
	 * memory, optionally with a given namespace (to avoid overlap).
	 *
	 * @param string $namespace The namespace for this cache to write to.
	 * @warning namespaces of the same name are shared!
	 */
	public function __construct($namespace = 'default') {
		$this->setNamespace($namespace);
		$this->clear();
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
		$namespace = $this->getNamespace();

		\ElggStaticVariableCache::$__cache[$namespace][$key] = $data;

		return true;
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
		$namespace = $this->getNamespace();

		if (isset(\ElggStaticVariableCache::$__cache[$namespace][$key])) {
			return \ElggStaticVariableCache::$__cache[$namespace][$key];
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
		$namespace = $this->getNamespace();

		unset(\ElggStaticVariableCache::$__cache[$namespace][$key]);

		return true;
	}

	/**
	 * Clears the cache for a particular namespace
	 *
	 * @return void
	 */
	public function clear() {
		$namespace = $this->getNamespace();

		if (!isset(\ElggStaticVariableCache::$__cache)) {
			\ElggStaticVariableCache::$__cache = array();
		}

		\ElggStaticVariableCache::$__cache[$namespace] = array();
	}
}
