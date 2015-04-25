<?php
namespace Elgg\Structs;

/**
 * A read-only interface to a group of items.
 *
 * @access private
 */
interface MutableCollection extends Collection {
	
	/**
	 * Adds the item to the collection.
	 * 
	 * Duplicates are allowed unless specified otherwise by a subinterface.
	 * 
	 * @param T $item
	 * 
	 * @return void
	 */
	public function add($item);
	
	/**
	 * Removes all items from this collection.
	 * 
	 * The collection is always empty after calling this.
	 * 
	 * @return void
	 */
	public function clear();

	/**
	 * Remove a single occurrence of the item from the collection.
	 * 
	 * @param T $item
	 * 
	 * @return void
	 */
	public function remove($item);
}