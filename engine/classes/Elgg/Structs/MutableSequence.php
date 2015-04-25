<?php
namespace Elgg\Structs;


/**
 * A sequence that can have items inserted and removed from any index.
 * 
 * @access private
 */
interface MutableSequence extends DoubleEndedStack, MutableCollection {
	/**
	 * Puts an item at the given position in the sequence,
	 * shifting all items after it over by one position.
	 * 
	 * @param int $index
	 * @param T   $item
	 * 
	 * @return void
	 */
	public function insertAt($index, $item);
	
	/**
	 * Removes an item at the given position in the sequence,
	 * shiften all items after it over by one position.
	 * 
	 * @param int $index
	 * 
	 * @return T $item
	 */
	public function removeAt($index);
	
	/**
	 * Removes and inserts items in a single operation.
	 * 
	 * TODO(ewinslow): allow collections to be passed in as $replacements
	 * 
	 * @param int   $index        Starting at this index
	 * @param int   $limit        Remove this many items
	 * @param array $replacements And insert these instead 
	 * 
	 * @return Sequence<T> The removed items
	 */
	public function splice($index, $limit = 0, array $replacements = []);
}
