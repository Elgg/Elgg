<?php
/**
 * ElggCache The elgg cache superclass.
 * This defines the interface for a cache (wherever that cache is stored).
 *
 * @package Elgg.Core
 * @subpackage Cache
 */
abstract class ElggCache implements
	// Override for array access
	ArrayAccess  {
	/**
	 * Variables for the cache object.
	 *
	 * @var array
	 */
	private $variables;

	/**
	 * Set the constructor.
	 */
	function __construct() {
		$this->variables = array();
	}

	/**
	 * Set a cache variable.
	 *
	 * @param string $variable
	 * @param string $value
	 */
	public function set_variable($variable, $value) {
		if (!is_array($this->variables)) {
			$this->variables = array();
		}

		$this->variables[$variable] = $value;
	}

	/**
	 * Get variables for this cache.
	 *
	 * @param string $variable
	 * @return mixed The variable or null;
	 */
	public function get_variable($variable) {
		if (isset($this->variables[$variable])) {
			return $this->variables[$variable];
		}

		return null;
	}

	/**
	 * Class member get overloading, returning key using $this->load defaults.
	 *
	 * @param string $key
	 * @return mixed
	 */
	function __get($key) {
		return $this->load($key);
	}

	/**
	 * Class member set overloading, setting a key using $this->save defaults.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return mixed
	 */
	function __set($key, $value) {
		return $this->save($key, $value);
	}

	/**
	 * Supporting isset, using $this->load() with default values.
	 *
	 * @param string $key The name of the attribute or metadata.
	 * @return bool
	 */
	function __isset($key) {
		return (bool)$this->load($key);
	}

	/**
	 * Supporting unsetting of magic attributes.
	 *
	 * @param string $key The name of the attribute or metadata.
	 */
	function __unset($key) {
		return $this->delete($key);
	}

	/**
	 * Save data in a cache.
	 *
	 * @param string $key
	 * @param string $data
	 * @return bool
	 */
	abstract public function save($key, $data);

	/**
	 * Load data from the cache using a given key.
	 *
	 * @param string $key
	 * @param int $offset
	 * @param int $limit
	 * @return mixed The stored data or false.
	 */
	abstract public function load($key, $offset = 0, $limit = null);

	/**
	 * Invalidate a key
	 *
	 * @param string $key
	 * @return bool
	 */
	abstract public function delete($key);

	/**
	 * Clear out all the contents of the cache.
	 *
	 */
	abstract public function clear();

	/**
	 * Add a key only if it doesn't already exist.
	 * Implemented simply here, if you extend this class and your caching engine provides a better way then
	 * override this accordingly.
	 *
	 * @param string $key
	 * @param string $data
	 * @return bool
	 */
	public function add($key, $data) {
		if (!isset($this[$key])) {
			return $this->save($key, $data);
		}

		return false;
	}

	// ARRAY ACCESS INTERFACE //////////////////////////////////////////////////////////
	function offsetSet($key, $value) {
		$this->save($key, $value);
	}

	function offsetGet($key) {
		return $this->load($key);
	}

	function offsetUnset($key) {
		if ( isset($this->key) ) {
			unset($this->key);
		}
	}

	function offsetExists($offset) {
		return isset($this->$offset);
	}
}