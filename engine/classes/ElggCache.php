<?php
/**
 * ElggCache The elgg cache superclass.
 * This defines the interface for a cache (wherever that cache is stored).
 *
 * @package    Elgg.Core
 * @subpackage Cache
 */
abstract class ElggCache implements ArrayAccess {
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
	 * @param string $variable Name
	 * @param string $value    Value
	 *
	 * @return void
	 *
	 * @deprecated 1.8 Use ElggCache:setVariable()
	 */
	public function set_variable($variable, $value) {
		elgg_deprecated_notice('ElggCache::set_variable() is deprecated by ElggCache::setVariable()', 1.8);
		$this->setVariable($variable, $value);
	}

	/**
	 * Set a cache variable.
	 *
	 * @param string $variable Name
	 * @param string $value    Value
	 *
	 * @return void
	 */
	public function setVariable($variable, $value) {
		if (!is_array($this->variables)) {
			$this->variables = array();
		}

		$this->variables[$variable] = $value;
	}

	/**
	 * Get variables for this cache.
	 *
	 * @param string $variable Name
	 *
	 * @return mixed The value or null;
	 *
	 * @deprecated 1.8 Use ElggCache::getVariable()
	 */
	public function get_variable($variable) {
		elgg_deprecated_notice('ElggCache::get_variable() is deprecated by ElggCache::getVariable()', 1.8);
		return $this->getVariable($variable);
	}

	/**
	 * Get variables for this cache.
	 *
	 * @param string $variable Name
	 *
	 * @return mixed The variable or null;
	 */
	public function getVariable($variable) {
		if (isset($this->variables[$variable])) {
			return $this->variables[$variable];
		}

		return null;
	}

	/**
	 * Class member get overloading, returning key using $this->load defaults.
	 *
	 * @param string $key Name
	 *
	 * @return mixed
	 */
	function __get($key) {
		return $this->load($key);
	}

	/**
	 * Class member set overloading, setting a key using $this->save defaults.
	 *
	 * @param string $key   Name
	 * @param mixed  $value Value
	 *
	 * @return mixed
	 */
	function __set($key, $value) {
		return $this->save($key, $value);
	}

	/**
	 * Supporting isset, using $this->load() with default values.
	 *
	 * @param string $key The name of the attribute or metadata.
	 *
	 * @return bool
	 */
	function __isset($key) {
		return (bool)$this->load($key);
	}

	/**
	 * Supporting unsetting of magic attributes.
	 *
	 * @param string $key The name of the attribute or metadata.
	 *
	 * @return bool
	 */
	function __unset($key) {
		return $this->delete($key);
	}

	/**
	 * Save data in a cache.
	 *
	 * @param string $key  Name
	 * @param string $data Value
	 *
	 * @return bool
	 */
	abstract public function save($key, $data);

	/**
	 * Load data from the cache using a given key.
	 *
	 * @todo $offset is a horrible variable name because it creates confusion
	 * with the ArrayAccess methods
	 *
	 * @param string $key    Name
	 * @param int    $offset Offset
	 * @param int    $limit  Limit
	 *
	 * @return mixed The stored data or false.
	 */
	abstract public function load($key, $offset = 0, $limit = null);

	/**
	 * Invalidate a key
	 *
	 * @param string $key Name
	 *
	 * @return bool
	 */
	abstract public function delete($key);

	/**
	 * Clear out all the contents of the cache.
	 *
	 * @return bool
	 */
	abstract public function clear();

	/**
	 * Add a key only if it doesn't already exist.
	 * Implemented simply here, if you extend this class and your caching engine
	 * provides a better way then override this accordingly.
	 *
	 * @param string $key  Name
	 * @param string $data Value
	 *
	 * @return bool
	 */
	public function add($key, $data) {
		if (!isset($this[$key])) {
			return $this->save($key, $data);
		}

		return false;
	}

	// ARRAY ACCESS INTERFACE //////////////////////////////////////////////////////////

	/**
	 * Assigns a value for the specified key
	 *
	 * @see ArrayAccess::offsetSet()
	 *
	 * @param mixed $key   The key (offset) to assign the value to.
	 * @param mixed $value The value to set.
	 *
	 * @return void
	 */
	function offsetSet($key, $value) {
		$this->save($key, $value);
	}

	/**
	 * Get the value for specified key
	 *
	 * @see ArrayAccess::offsetGet()
	 *
	 * @param mixed $key The key (offset) to retrieve.
	 *
	 * @return mixed
	 */
	function offsetGet($key) {
		return $this->load($key);
	}

	/**
	 * Unsets a key.
	 *
	 * @see ArrayAccess::offsetUnset()
	 *
	 * @param mixed $key The key (offset) to unset.
	 *
	 * @return void
	 */
	function offsetUnset($key) {
		if (isset($this->$key)) {
			unset($this->$key);
		}
	}

	/**
	 * Does key exist
	 *
	 * @see ArrayAccess::offsetExists()
	 *
	 * @param mixed $key A key (offset) to check for.
	 *
	 * @return bool
	 */
	function offsetExists($key) {
		return isset($this->$key);
	}
}
