<?php
namespace Elgg\Notifications;

/**
 * Database queue mock for testing event object serialization
 */
class DatabaseQueueMock implements \Elgg\Queue\Queue {

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
		$this->queue = array();
	}

	/**
	 * {@inheritdoc}
	 */
	public function size() {
		return count($this->queue);
	}
}

