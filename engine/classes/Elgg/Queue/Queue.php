<?php
namespace Elgg\Queue;

/**
 * Queue interface
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Queue
 * @since      1.9.0
 */
interface Queue {
	/**
	 * Add an item to the queue
	 *
	 * @param mixed $item Item to add to queue
	 * @return bool
	 */
	public function enqueue($item);

	/**
	 * Remove an item from the queue
	 *
	 * @return mixed
	 */
	public function dequeue();

	/**
	 * Clear all items from the queue
	 *
	 * @return void
	 */
	public function clear();

	/**
	 * Get the size of the queue
	 *
	 * @return int
	 */
	public function size();
}
