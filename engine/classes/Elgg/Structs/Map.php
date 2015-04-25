<?php
namespace Elgg\Structs;

/**
 * A collection of items which all have associated keys.
 * 
 * Generics:
 *  K -- The type of the keys in the map (not just strings!)
 *  V -- The type of the values in the map
 * 
 * @access private
 */
interface Map/*<K,V>*/ extends Collection/*<V>*/ {
	/**
	 * Gets a collection of the key/value tuples (i.e. [$key, $value]) in this map.
	 * 
	 * @return Collection<[K,V]>
	 */
	public function entries();
	
	/**
	 * @return Map<K,V> Maintains key associations after filter is applied.
	 * @inheritDoc
	 */
	public function filter(callable $filter);

	/**
	 * Gets the item associated with a given key.
	 * 
	 * Throws an exception if no item can be found or generated for the key.
	 * 
	 * @param K $key
	 * 
	 * @return V The item.
	 */
	public function get($key);
	
	/**
	 * Indicates whether the given key has been set for this map.
	 * 
	 * @param K $key
	 * 
	 * @return boolean
	 */
	public function has($key);

	/**
	 * Get a set of all the keys that have associated values.
	 * 
	 * @return Set<K>
	 */
	public function keys();

	/**
	 * @param callable(V):R $mapper
	 * 
	 * @return Map<K,R> Maintains key associations after mapper is applied.
	 * @inheritDoc
	 */
	public function map(callable $mapper);
}