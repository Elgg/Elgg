<?php

namespace Elgg\Structs;

/**
 * A key-value store where:
 * 
 *  * all the keys have the same type
 *  * all the values have the same type
 * 
 * The type of the keys doesn't have to be the same as the type of the values.
 * 
 * @since 2.0.0
 * @access private
 */
interface Map/*<K,V>*/ {
	/**
	 * Gets the value associated with the given key, or null.
	 * 
	 * @param K $key The key
	 * 
	 * @return V
	 */
	public function get(/*K*/ $key);

	/**
	 * Indicates whether the given key has an associated value.
	 * 
	 * @param K $key The key
	 * 
	 * @return bool
	 */
	public function has(/*K*/ $key);
	
	/**
	 * Gets a read-only set of the values in this map.
	 * 
	 * @return Collection<K>
	 */
	public function keys();
	
	/**
	 * Gets a read-only collection of the values in this map.
	 * 
	 * @return Collection<V>
	 */
	public function values();
}