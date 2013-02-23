<?php


/**
 * FIFO Queue interface
 */
interface Elgg_Util_FifoQueue {
	/**
	 * Add an item to the queue
	 *
	 * @param mixed $item
	 * @return bool
	 */
	public function enqueue($item);

	/**
	 * Remove the oldest item from the queue
	 *
	 * @return mixed
	 */
	public function dequeue();

	/**
	 * Clear all items from the queue
	 */
	public function clear();
}
