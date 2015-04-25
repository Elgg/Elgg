<?php
namespace Elgg\Structs;

/**
 * A sequence of items that can have items inserted at an end and removed at an end. 
 * 
 * The most basic queues will pop items in the same order they are pushed (FIFO,
 * aka first-in-first-out), but extensions may specify a different behavior.
 * For example, Stacks are LIFO and PriorityQueues use a custom algorithm.
 * 
 * @access private
 */
interface Queue {
	/**
	 * Takes a look at the next item that will be popped, without removing it.
	 * 
	 * @return T
	 */
	public function peek();
	
	/**
	 * Removes an item from the queue and returns it.
	 *
	 * @return T
	 */
	public function pop();

	/**
	 * Adds an item to the queue.
	 *
	 * @param T $item
	 */
	public function push($item);
}
