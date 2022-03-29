<?php
/**
 * \ElggCache The elgg cache superclass.
 * This defines the interface for a cache (wherever that cache is stored).
 */
abstract class ElggCache implements \ArrayAccess {
	/**
	 * Variables for the cache object.
	 *
	 * @var array
	 */
	private $variables = [];

	/**
	 * @var bool
	 */
	protected $disabled = false;

	/**
	 * Set the constructor.
	 */
	public function __construct() {
	}
	
	/**
	 * Set a cache variable.
	 *
	 * @param string $variable Name
	 * @param mixed  $value    Value
	 *
	 * @return void
	 */
	public function setVariable(string $variable, $value): void {
		$this->variables[$variable] = $value;
	}

	/**
	 * Get variables for this cache.
	 *
	 * @param string $variable Name
	 *
	 * @return mixed The variable or null;
	 */
	public function getVariable(string $variable) {
		return $this->variables[$variable] ?? null;
	}

	/**
	 * Class member get overloading, returning key using $this->load defaults.
	 *
	 * @param string $key Name
	 *
	 * @return mixed
	 */
	public function __get(string $key) {
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
	public function __set(string $key, $value): void {
		$this->save($key, $value);
	}

	/**
	 * Supporting isset, using $this->load() with default values.
	 *
	 * @param string $key The name of the attribute or metadata.
	 *
	 * @return bool
	 */
	public function __isset(string $key): bool {
		return (bool) $this->load($key);
	}

	/**
	 * Supporting unsetting of magic attributes.
	 *
	 * @param string $key The name of the attribute or metadata.
	 *
	 * @return void
	 */
	public function __unset(string $key): void {
		$this->delete($key);
	}

	/**
	 * Save data in a cache.
	 *
	 * @param string $key          Name
	 * @param mixed  $data         Value
	 * @param int    $expire_after Number of seconds to expire the cache after
	 *
	 * @return bool
	 */
	abstract public function save($key, $data, $expire_after = null);

	/**
	 * Load data from the cache using a given key.
	 *
	 * @param string $key Name
	 *
	 * @return mixed|null The stored data or null if it's a miss
	 */
	abstract public function load($key);

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
	 * Invalidate the contents of the cache.
	 *
	 * @return bool
	 */
	abstract public function invalidate();
	
	/**
	 * Purge old/stale contents of the cache.
	 *
	 * @return bool
	 */
	abstract public function purge();

	/**
	 * Disable cache
	 * Do not write or read from cache
	 * @return void
	 */
	public function disable(): void {
		$this->disabled = true;
	}

	/**
	 * Enable disabled cache
	 * @return void
	 */
	public function enable(): void {
		$this->disabled = false;
	}

	/**
	 * Populate cache from an array of key => values
	 *
	 * @param array $values        Values
	 * @param int   $expires_after Expiration duration
	 *
	 * @return void
	 */
	public function populate(array $values, int $expires_after = null): void {
		$this->clear();
		foreach ($values as $key => $value) {
			$this->save($key, $value, $expires_after);
		}
	}

	/**
	 * Add a key only if it doesn't already exist.
	 * Implemented simply here, if you extend this class and your caching engine
	 * provides a better way then override this accordingly.
	 *
	 * @param string $key  Name
	 * @param mixed  $data Value
	 *
	 * @return bool
	 */
	public function add(string $key, $data): bool {
		if (!isset($this[$key])) {
			return $this->save($key, $data);
		}

		return false;
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
	#[\ReturnTypeWillChange]
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
	#[\ReturnTypeWillChange]
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
	#[\ReturnTypeWillChange]
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
	#[\ReturnTypeWillChange]
	public function offsetExists($key) {
		return isset($this->$key);
	}
}
