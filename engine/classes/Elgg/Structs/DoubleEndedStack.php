<?php
namespace Elgg\Structs;

/**
 * A Stack that allows you to insert/remove from both ends.
 * 
 * These operations are push/pop for the "top" or "end" of the stack,
 * and shift/unshift for the "bottom" or "front" of the stack.
 * 
 * This is also known as a Deque or DoubleEndedQueue.
 * 
 * @see http://en.wikipedia.org/wiki/Double-ended_queue
 * 
 * Generics
 *  V -- The type of the items
 * 
 * @access private
 */
interface DoubleEndedStack extends Stack {
	/**
	 * Removes an item from the bottom of the stack.
	 * 
	 * @return V The removed item
	 */
	public function shift();
	
	/**
	 * Inserts the item onto the bottom of the stack.
	 * 
	 * @param V $item
	 * 
	 * @return void
	 */
	public function unshift($item);
}
