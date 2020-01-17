<?php

namespace Elgg\Queue;

/**
 * FIFO queue that is memory based (not persistent)
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @internal
 *
 * @since 1.9.0
 */
class MemoryQueue implements \Elgg\Queue\Queue {

	/* @var array */
	protected $queue = [];

	/**
	 * Create a queue
	 */
	public function __construct() {
		$this->queue = [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function enqueue($item) {
		return (bool) array_push($this->queue, $item);
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
		$this->queue = [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function size() {
		return count($this->queue);
	}
}
