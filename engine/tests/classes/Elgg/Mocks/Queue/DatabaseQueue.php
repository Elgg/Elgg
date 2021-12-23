<?php

namespace Elgg\Mocks\Queue;

/**
 * Database queue mock for testing event object serialization
 */
class DatabaseQueue implements \Elgg\Queue\Queue {

	/**
	 * @var array
	 */
	protected $queue = [];

	/**
	 * {@inheritdoc}
	 */
	public function enqueue($item) {
		return (bool)array_push($this->queue, serialize($item));
	}

	/**
	 * {@inheritdoc}
	 */
	public function dequeue() {
		$item = array_shift($this->queue);
		if ($item) {
			return unserialize($item);
		}
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
