<?php

namespace Elgg\Upgrade;

/**
 * Result of a single BatchUpgrade run
 *
 * @internal
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
	 *
	 * @return void
	 */
	public function addError($message): void {
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
	public function getErrors(): array {
		return $this->errors;
	}

	/**
	 * Increment failure count
	 *
	 * This must be called every time an item fails to get upgraded.
	 *
	 * @param int $num Number of items (defaults to 1)
	 *
	 * @return void
	 */
	public function addFailures(int $num = 1): void {
		$this->failure_count += $num;
	}

	/**
	 * Get count of failures within the current batch
	 *
	 * @return int $failure_count Amount of failures
	 */
	public function getFailureCount(): int {
		return $this->failure_count;
	}

	/**
	 * Set an item (or items) as successfully upgraded
	 *
	 * @param int $num Amount if items (defaults to one)
	 *
	 * @return void
	 */
	public function addSuccesses(int $num = 1): void {
		$this->success_count += $num;
	}

	/**
	 * Get count of successfully upgraded items within the current batch
	 *
	 * @return int $failure_count Amount of failures
	 */
	public function getSuccessCount(): int {
		return $this->success_count;
	}

	/**
	 * Mark the upgrade as complete (not necessarily successful)
	 *
	 * @return void
	 */
	public function markComplete(): void {
		$this->is_complete = true;
	}

	/**
	 * Has the upgrade been marked complete?
	 *
	 * @return bool
	 */
	public function wasMarkedComplete(): bool {
		return $this->is_complete === true;
	}

	/**
	 * Export to reports array
	 *
	 * @return array
	 */
	public function toArray(): array {
		return [
			'errors' => $this->getErrors(),
			'numErrors' => $this->getFailureCount(),
			'numSuccess' => $this->getSuccessCount(),
			'isComplete' => $this->wasMarkedComplete(),
		];
	}
}
