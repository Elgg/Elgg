<?php

/**
 * FIFO queue that is memory based (not persistent)
 * 
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 * 
 * @package    Elgg.Core
 * @subpackage Queue
 * @since      1.9.0
 */
class Elgg_Queue_MemoryQueue implements Elgg_Queue_Queue {

	/* @var array */
	protected $queue = array();

	/**
	 * Create a queue
	 */
	public function __construct() {
		$this->queue = array();
	}

	/**
	 * {@inheritdoc}
	 */
	public function enqueue($item) {
		return (bool)array_push($this->queue, $item);
	}

	/**
	 * {@inheritdoc}
	 */
	public function dequeue() {
		return array_shift($this->queue);
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear() {
		$this->queue = array();
	}

	/**
	 * {@inheritdoc}
	 */
	public function size() {
		return count($this->queue);
	}
}
