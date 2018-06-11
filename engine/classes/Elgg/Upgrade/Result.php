<?php

namespace Elgg\Upgrade;

/**
 * Result of a single BatchUpgrade run
 */
final class Result {

	private $errors = [];

	private $failure_count = 0;

	private $success_count = 0;

	private $is_complete = false;

	/**
	 * Add new error message to the batch
	 *
	 * @param string|string[] $message Error messages
	 * @return void
	 */
	public function addError($message) {
		if (is_array($message)) {
			$this->errors = $this->errors + $message;
		} else {
			$this->errors[] = $message;
		}
	}

	/**
	 * Get error messages
	 *
	 * @return array $errors Array of error messages
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * Increment failure count
	 *
	 * This must be called every time an item fails to get upgraded.
	 *
	 * @param int $num Number of items (defaults to 1)
	 * @return void
	 */
	public function addFailures($num = 1) {
		$this->failure_count += $num;
	}

	/**
	 * Get count of failures within the current batch
	 *
	 * @return int $failure_count Amount of failures
	 */
	public function getFailureCount() {
		return $this->failure_count;
	}

	/**
	 * Set an item (or items) as successfully upgraded
	 *
	 * @param int $num Amount if items (defaults to one)
	 * @return void
	 */
	public function addSuccesses($num = 1) {
		$this->success_count += $num;
	}

	/**
	 * Get count of successfully upgraded items within the current batch
	 *
	 * @return int $failure_count Amount of failures
	 */
	public function getSuccessCount() {
		return $this->success_count;
	}

	/**
	 * Mark the upgrade as complete (not necessarily successful)
	 *
	 * @return void
	 */
	public function markComplete() {
		$this->is_complete = true;
	}

	/**
	 * Has the upgrade been marked complete?
	 *
	 * @internal
	 * @access private
	 * @return bool
	 */
	public function wasMarkedComplete() {
		return $this->is_complete === true;
	}

	/**
	 * Export to reports array
	 * @return array
	 */
	public function toArray() {
		return [
			'errors' => $this->getErrors(),
			'numErrors' => $this->getFailureCount(),
			'numSuccess' => $this->getSuccessCount(),
			'isComplete' => $this->wasMarkedComplete(),
		];
	}
}