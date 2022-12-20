<?php

namespace Elgg\Cache;

use Elgg\Exceptions\RangeException;

/**
 * Least Recently Used Cache
 *
 * A fixed sized cache that removes the element used last when it reaches its
 * size limit.
 *
 * Based on https://github.com/cash/LRUCache
 *
 * @internal
 */
class LRUCache implements \ArrayAccess {
	
	/**
	 * @var int
	 */
	protected $maximumSize;

	/**
	 * The front of the array contains the LRU element
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Create a LRU Cache
	 *
	 * @param int $size The size of the cache
	 *
	 * @throws RangeException
	 */
	public function __construct(int $size) {
		if ($size <= 0) {
			throw new RangeException('"size" must be greater than 0');
		}
		
		$this->maximumSize = $size;
	}

	/**
	 * Get the value cached with this key
	 *
	 * @param int|string $key     The key. Strings that are ints are cast to ints.
	 * @param mixed      $default The value to be returned if key not found. (Optional)
	 *
	 * @return mixed
	 */
	public function get($key, $default = null) {
		if ($this->containsKey($key)) {
			$this->recordAccess($key);
			
			return $this->data[$key];
		}
		
		return $default;
	}

	/**
	 * Add something to the cache
	 *
	 * @param int|string $key   The key. Strings that are ints are cast to ints.
	 * @param mixed      $value The value to cache
	 *
	 * @return void
	 */
	public function set($key, $value) {
		if ($this->containsKey($key)) {
			$this->data[$key] = $value;
			$this->recordAccess($key);
		} else {
			$this->data[$key] = $value;
			if ($this->size() > $this->maximumSize) {
				// remove least recently used element (front of array)
				reset($this->data);
				unset($this->data[key($this->data)]);
			}
		}
	}

	/**
	 * Get the number of elements in the cache
	 *
	 * @return int
	 */
	public function size() {
		return count($this->data);
	}

	/**
	 * Does the cache contain an element with this key
	 *
	 * @param int|string $key The key
	 *
	 * @return boolean
	 */
	public function containsKey($key) {
		return array_key_exists($key, $this->data);
	}

	/**
	 * Remove the element with this key.
	 *
	 * @param int|string $key The key
	 *
	 * @return mixed Value or null if not set
	 */
	public function remove($key) {
		if ($this->containsKey($key)) {
			$value = $this->data[$key];
			unset($this->data[$key]);
			return $value;
		}
		
		return null;
	}

	/**
	 * Clear the cache
	 *
	 * @return void
	 */
	public function clear() {
		$this->data = [];
	}

	/**
	 * Moves the element from current position to end of array
	 *
	 * @param int|string $key The key
	 *
	 * @return void
	 */
	protected function recordAccess($key) {
		$value = $this->data[$key];
		unset($this->data[$key]);
		$this->data[$key] = $value;
	}

	/**
	 * Assigns a value for the specified key
	 *
	 * @param int|string $offset The key to assign the value to.
	 * @param mixed      $value  The value to set.
	 *
	 * @return void
	 * @see \ArrayAccess::offsetSet()
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet($offset, $value) {
		$this->set($offset, $value);
	}

	/**
	 * Get the value for specified key
	 *
	 * @param int|string $offset The key to retrieve.
	 *
	 * @return mixed
	 * @see \ArrayAccess::offsetGet()
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet($offset) {
		return $this->get($offset);
	}

	/**
	 * Unsets a key.
	 *
	 * @param int|string $offset The key to unset.
	 *
	 * @return void
	 * @see \ArrayAccess::offsetUnset()
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset($offset) {
		$this->remove($offset);
	}

	/**
	 * Does key exist?
	 *
	 * @param int|string $offset A key to check for.
	 *
	 * @return boolean
	 * @see \ArrayAccess::offsetExists()
	 */
	#[\ReturnTypeWillChange]
	public function offsetExists($offset) {
		return $this->containsKey($offset);
	}
}
