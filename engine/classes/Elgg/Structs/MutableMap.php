<?php
namespace Elgg\Structs;

/**
 * A collection of items which all have associated keys.
 *
 * @access private
 */
interface MutableMap extends Map {

	/**
	 * Sets the value associated with $key to $item.
	 * 
	 * @param K $key
	 * @param V $item
	 * 
	 * @return void
	 */
	public function set($key, $item);

	/**
	 * Removes the key and its associated item from this map.
	 * 
	 * @param K $key
	 * 
	 * @return V The removed item.
	 */
	public function delete($key);
}