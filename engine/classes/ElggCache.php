<?php
/**
 * \ElggCache The elgg cache superclass.
 * This defines the interface for a cache (wherever that cache is stored).
 *
 * @package    Elgg.Core
 * @subpackage Cache
 */
abstract class ElggCache implements \ArrayAccess {
	/**
	 * Variables for the cache object.
	 *
	 * @var array
	 */
	private $variables;

	/**
	 * Set the constructor.
	 */
	public function __construct() {
		$this->variables = array();
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
	public function __get($key) {
		return $this->load($key);
	}

	/**
	 * Class member set overloading, setting a key using $this->save defaults.
	 *
	 * @param string $key   Name
	 * @param mixed  $value Value
	 *
	 * @return void
	 */
	public function __set($key, $value) {
		$this->save($key, $value);
	}

	/**
	 * Supporting isset, using $this->load() with default values.
	 *
	 * @param string $key The name of the attribute or metadata.
	 *
	 * @return bool
	 */
	public function __isset($key) {
		return (bool)$this->load($key);
	}

	/**
	 * Supporting unsetting of magic attributes.
	 *
	 * @param string $key The name of the attribute or metadata.
	 *
	 * @return bool
	 */
	public function __unset($key) {
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
	 * with the \ArrayAccess methods
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

	/**
	 * Cache the output of an expensive function, if the system cache is enabled
	 *
	 * @param string   $key  Cache key
	 * @param callable $func Function that requires no arguments. The result must be serializable.
	 * @param int      $ttl  TTL for result (seconds). 0 for no expiration
	 *
	 * @return mixed
	 */
	public function cacheCall($key, callable $func, $ttl = 0) {
		if (!elgg_is_system_cache_enabled()) {
			return call_user_func($func);
		}

		$cached = $this->load($key);
		if ($cached && preg_match('~^\d+,(?:string|serialized),~', $cached)) {
			list($saved_at, $type, $txt) = explode(',', $cached, 3);
			if (!$ttl || (time() < $saved_at + $ttl)) {
				if ($type === 'string') {
					return $txt;
				}
				return unserialize($txt);
			}
		}

		$return = call_user_func($func);
		if (is_string($return)) {
			$txt = $return;
			$type = 'string';
		} else {
			$txt = serialize($return);
			$type = 'serialized';
		}

		$this->save($key, implode(',', [time(), $type, $txt]));

		return $return;
	}

	// ARRAY ACCESS INTERFACE //////////////////////////////////////////////////////////

	/**
	 * Assigns a value for the specified key
	 *
	 * @see \ArrayAccess::offsetSet()
	 *
	 * @param mixed $key   The key (offset) to assign the value to.
	 * @param mixed $value The value to set.
	 *
	 * @return void
	 */
	public function offsetSet($key, $value) {
		$this->save($key, $value);
	}

	/**
	 * Get the value for specified key
	 *
	 * @see \ArrayAccess::offsetGet()
	 *
	 * @param mixed $key The key (offset) to retrieve.
	 *
	 * @return mixed
	 */
	public function offsetGet($key) {
		return $this->load($key);
	}

	/**
	 * Unsets a key.
	 *
	 * @see \ArrayAccess::offsetUnset()
	 *
	 * @param mixed $key The key (offset) to unset.
	 *
	 * @return void
	 */
	public function offsetUnset($key) {
		if (isset($this->$key)) {
			unset($this->$key);
		}
	}

	/**
	 * Does key exist
	 *
	 * @see \ArrayAccess::offsetExists()
	 *
	 * @param mixed $key A key (offset) to check for.
	 *
	 * @return bool
	 */
	public function offsetExists($key) {
		return isset($this->$key);
	}
}
