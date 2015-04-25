<?php
namespace Elgg\Structs;

use IteratorAggregate;

/**
 * A read-only interface to a group/bag of items.
 *
 * @access private
 */
interface Collection extends Countable, IteratorAggregate {
	
	/**
	 * Returns true iff the item is in this collection at least once.
	 * 
	 * @param T $item The object or value to check for 
	 * 
	 * @return boolean
	 */
	public function contains($item);

	/**
	 * Returns a new collection only containing the elements which pass the filter.
	 * 
	 * @param callable $filter Receives an item. Return true to keep the item.
	 * 
	 * @return Collection<T>
	 */
	public function filter(callable $filter);
	
	/**
	 * Returns a new collection created by applying the $mapper to each item in
	 * this collection.
	 * 
	 * The callable is not guaranteed to execute immediately for each item, so
	 * do not pass in a callable with any side effects.
	 * 
	 * @param callable $mapper Receives an item and returns the mapped value
	 * 
	 * @return Collection<T>
	 */
	public function map(callable $mapper);
	
	/**
	 * Returns an array containing all the items in this collection.
	 * 
	 * @return T[]
	 */
	public function toArray();
	
	/**
	 * Narrows down the collection to based on item properties.
	 * 
	 * This only works for collections of object and/or array, not int or string.
	 * 
	 * For example:
	 * ```
	 * function(Collection $entities) {
	 * 		return $entities->where(['container_guid' => 12345]);
	 * }
	 * 
	 * @param array $options How properties should be compared
	 * 
	 * @return Collection<T>
	 */
	public function where(array $options);
}