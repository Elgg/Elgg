<?php

class Elgg_Util_MemoryQueue implements Elgg_Util_FifoQueue {

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
}
