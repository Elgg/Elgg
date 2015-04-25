<?php
namespace Elgg\Structs;

/**
 * A collection whose elements are ordered such that it makes
 * sense to ask which is the "nth" item. Normally this would be called a "List"
 * but that is a reserved keyword in PHP used for array destructuring.
 * 
 * Subinterfaces may provide functionality for reordering items.
 *
 * @access private
 */
interface Sequence extends Collection {
	
	/**
	 * @return Sequence Maintains relative order of items, but not indexes.
	 * @inheritDoc
	 */
	public function filter(callable $filter);
	
	/**
	 * Returns the first item in the sequence.
	 * 
	 * Alias for $this->getAt(0), essentially.
	 * 
	 * @return T
	 */
	public function first();
	
	/**
	 * Returns the item at the given index.
	 * 
	 * Throws an exception for invalid values of index:
	 *  - Any value less than 0
	 *  - Any value greater than the size of the collection
	 * 
	 * @param int $index
	 * 
	 * @return T
	 */
	public function getAt($index);
	
	/**
	 * Returns the index of the first occurence of the item in this sequence.
	 * 
	 * @param T $item
	 * 
	 * @return int Returns -1 if not preset in the set.
	 */
	public function indexOf($item);
	
	/**
	 * Returns the last item in a non-empty collection.
	 * 
	 * Alias for:
	 *  - $this->reverse()->getAt(0)
	 *  - $this->getAt(count($this) - 1)
	 * 
	 * @return T
	 */
	public function last();
	
	/**
	 * @return Sequence<R> Maintains the order and index of items.
	 * @inheritDoc
	 */
	public function map(callable $mapper);

	/**
	 * Gets a new sequence with the items sorted from low to high (ascending)
	 * on the given property.
	 * 
	 * @example To get the most recently created items first, assuming time_created
	 * stores the creation date:
	 * 
	 * ```php
	 * function mostRecent(Sequence $sequence) {
	 * 	return $sequence->orderBy('time_created')->reverse();
	 * }
	 * ```
	 * 
	 * @return Sequence<T>
	 */
	public function orderBy($property);
	
	/**
	 * Gets a new sequence with all of its items in reverse order of the current one.
	 * 
	 * @return Sequence<T>
	 */
	public function reverse();
	
	/**
	 * Gets a new Sequence that represents a section (slice) of the current sequence.
	 * 
	 * @param int $offset Starting at this index
	 * @param int $limit  Return this many items
	 * 
	 * @return Sequence<T>
	 */
	public function slice($offset = 0, $limit = 0);
}
