<?php

namespace Elgg\Upgrade;

use Elgg\Cli\Progress;
use Elgg\Exceptions\RuntimeException;
use Elgg\Logger;
use Elgg\Traits\Loggable;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Upgrade loop
 * Executes upgrade batches for a given duration of time
 */
class Loop {

	use Loggable;

	/**
	 * @var Batch|false
	 */
	protected $batch;

	protected int $max_duration;

	protected int $count;

	protected int $processed;

	protected int $offset;

	/**
	 * Constructor
	 *
	 * @param \ElggUpgrade $upgrade  Upgrade instance
	 * @param Result       $result   Upgrade result
	 * @param Progress     $progress CLI progress helper
	 * @param Logger       $logger   Logger
	 *
	 * @throws RuntimeException
	 */
	public function __construct(
		protected \ElggUpgrade $upgrade,
		protected Result $result,
		protected Progress $progress,
		Logger $logger
	) {
		$this->setLogger($logger);
		
		// Get the class taking care of the actual upgrading
		$this->batch = $upgrade->getBatch();
		if (!$this->batch) {
			throw new RuntimeException(elgg_echo('admin:upgrades:error:invalid_batch', [
				$upgrade->getDisplayName(),
				$upgrade->guid
			]));
		}

		$this->count = $this->batch->countItems();
		$this->processed = (int) $upgrade->processed;
		$this->offset = (int) $upgrade->offset;
	}

	/**
	 * Run upgrade loop for a preset number of seconds
	 *
	 * @param int|null $max_duration Maximum loop duration
	 *
	 * @return void
	 */
	public function loop(?int $max_duration = null): void {
		$started = microtime(true);

		$this->upgrade->setStartTime();
		$progress = $this->progress->start($this->upgrade->getDisplayName(), $this->count);

		while ($this->canContinue($started, $max_duration)) {
			$this->runBatch($progress);
		}

		$this->progress->finish($progress);

		$this->upgrade->processed = $this->processed;
		$this->upgrade->offset = $this->offset;

		if (!$this->isCompleted()) {
			return;
		}

		// Upgrade is finished
		if ($this->result->getFailureCount()) {
			// The upgrade was finished with errors. Reset offset
			// and errors so the upgrade can start from a scratch
			// if attempted to run again.
			$this->upgrade->processed = 0;
			$this->upgrade->offset = 0;
		} else {
			// Everything has been processed without errors
			// so the upgrade can be marked as completed.
			$this->upgrade->setCompleted();
			$this->result->markComplete();
		}

		$this->report();
	}

	/**
	 * Run batch
	 *
	 * @param ProgressBar $progress Progress bar helper
	 *
	 * @return void
	 */
	protected function runBatch(ProgressBar $progress): void {
		try {
			$this->batch->run($this->result, $this->offset);
		} catch (\Exception $e) {
			$this->getLogger()->error($e);

			$this->result->addError($e->getMessage());
			$this->result->addFailures(1);
		}

		$failure_count = $this->result->getFailureCount();
		$success_count = $this->result->getSuccessCount();

		$total = $this->upgrade->processed + $failure_count + $success_count;

		$progress->advance($total - $this->processed);

		if ($this->batch->needsIncrementOffset()) {
			// Offset needs to incremented by the total amount of processed
			// items so the upgrade we won't get stuck upgrading the same
			// items over and over.
			$this->offset = $total;
		} else {
			// Offset doesn't need to be incremented, so we mark only
			// the items that caused a failure.
			$this->offset = $this->upgrade->offset + $failure_count;
		}

		$this->processed = $total;
	}

	/**
	 * Report loop results
	 *
	 * @return void
	 */
	protected function report(): void {
		$upgrade_name = $this->upgrade->getDisplayName();

		if ($this->upgrade->isCompleted()) {
			$ts = $this->upgrade->getCompletedTime();
			$dt = new \DateTime();
			$dt->setTimestamp((int) $ts);
			$format = elgg_get_config('date_format') ?: DATE_ATOM;

			if ($this->result->getFailureCount()) {
				elgg_register_error_message(elgg_echo('admin:upgrades:completed:errors', [
					$upgrade_name,
					$dt->format($format),
					$this->result->getFailureCount(),
				]));
			} else {
				elgg_register_success_message(elgg_echo('admin:upgrades:completed', [
					$upgrade_name,
					$dt->format($format),
				]));
			}
		} else {
			elgg_register_error_message(elgg_echo('admin:upgrades:failed', [$upgrade_name]));
		}

		foreach ($this->result->getErrors() as $error) {
			$this->getLogger()->error($error);
		}
	}

	/**
	 * Check if the loop can and should continue
	 *
	 * @param float    $started      Timestamp of the loop initiation
	 * @param int|null $max_duration Maximum loop duration
	 *
	 * @return bool
	 */
	protected function canContinue($started, ?int $max_duration = null): bool {
		if (!isset($max_duration)) {
			$max_duration = (int) elgg_get_config('batch_run_time_in_secs');
		}

		if ($max_duration > 0 && (microtime(true) - $started) >= $max_duration) {
			return false;
		}

		return !$this->isCompleted();
	}

	/**
	 * Check if upgrade has completed
	 *
	 * @return bool
	 */
	protected function isCompleted(): bool {
		if ($this->batch->shouldBeSkipped()) {
			return true;
		}

		if ($this->result->wasMarkedComplete()) {
			return true;
		}
		
		if ($this->count === Batch::UNKNOWN_COUNT) {
			// the batch reports an unknown count and should mark the Result as complete when it's done
			return false;
		}
		
		if (!$this->batch->needsIncrementOffset()) {
			// the batch has some way of marking progress (like a delete) and the count items should reflect this
			return ($this->batch->countItems() - $this->result->getFailureCount()) <= 0;
		}

		return $this->processed >= $this->count;
	}
}
