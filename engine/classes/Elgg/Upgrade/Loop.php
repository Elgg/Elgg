<?php

namespace Elgg\Upgrade;

use Elgg\Cli\Progress;
use Elgg\Loggable;
use Elgg\Logger;
use ElggUpgrade;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Upgrade loop
 * Executes upgrade batches for a given duration of time
 */
class Loop {

	use Loggable;

	/**
	 * @var ElggUpgrade
	 */
	protected $upgrade;

	/**
	 * @var Result
	 */
	protected $result;

	/**
	 * @var Batch|false
	 */
	protected $batch;

	/**
	 * @var int
	 */
	protected $max_duration;

	/**
	 * @var int
	 */
	protected $count;

	/**
	 * @var int
	 */
	protected $processed;

	/**
	 * @var int
	 */
	protected $offset;

	/**
	 * @var Progress
	 */
	protected $progress;

	/**
	 * Constructor
	 *
	 * @param ElggUpgrade $upgrade  Upgrade instance
	 * @param Result      $result   Upgrade result
	 * @param Progress    $progress CLI progress helper
	 * @param Logger      $logger   Logger
	 */
	public function __construct(
		ElggUpgrade $upgrade,
		Result $result,
		Progress $progress,
		Logger $logger
	) {
		$this->upgrade = $upgrade;

		// Get the class taking care of the actual upgrading
		$this->batch = $upgrade->getBatch();
		if (!$this->batch) {
			throw new \RuntimeException(elgg_echo('admin:upgrades:error:invalid_batch', [
				$upgrade->getDisplayName(),
				$upgrade->guid
			]));
		}

		$this->result = $result;
		$this->progress = $progress;
		$this->logger = $logger;

		$this->count = $this->batch->countItems();
		$this->processed = (int) $upgrade->processed;
		$this->offset = (int) $upgrade->offset;
	}

	/**
	 * Run upgrade loop for a preset number of seconds
	 *
	 * @param int|false $max_duration Maximum loop duration
	 *
	 * @return void
	 */
	public function loop($max_duration = null) {

		$started = microtime(true);

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
	protected function runBatch(ProgressBar $progress) {
		try {
			$this->batch->run($this->result, $this->offset);
		} catch (\Exception $e) {
			$this->logger->error($e);

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
	 * @return void
	 */
	protected function report() {
		$upgrade_name = $this->upgrade->getDisplayName();

		if ($this->upgrade->isCompleted()) {
			$ts = $this->upgrade->getCompletedTime();
			$dt = new \DateTime();
			$dt->setTimestamp((int) $ts);
			$format = elgg_get_config('date_format') ? : DATE_ISO8601;

			if ($this->result->getFailureCount()) {
				$msg = elgg_echo('admin:upgrades:completed:errors', [
					$upgrade_name,
					$dt->format($format),
					$this->result->getFailureCount(),
				]);

				register_error($msg);
			} else {
				$msg = elgg_echo('admin:upgrades:completed', [
					$upgrade_name,
					$dt->format($format),
				]);

				system_message($msg);
			}
		} else {
			$msg = elgg_echo('admin:upgrades:failed', [
				$upgrade_name
			]);

			register_error($msg);
		}

		foreach ($this->result->getErrors() as $error) {
			$this->logger->log(LogLevel::ERROR, $error);
		}
	}

	/**
	 * Check if the loop cand and should continue
	 *
	 * @param int  $started      Timestamp of the loop initiation
	 * @param null $max_duration Maximum loop duration
	 *
	 * @return bool
	 */
	protected function canContinue($started, $max_duration = null) {
		if (!isset($max_duration)) {
			$max_duration = elgg_get_config('batch_run_time_in_secs');
		}

		if ($max_duration && (microtime(true) - $started) >= $max_duration) {
			return false;
		}

		return !$this->isCompleted();
	}

	/**
	 * Check if upgrade has completed
	 * @return bool
	 */
	protected function isCompleted() {
		if ($this->batch->shouldBeSkipped()) {
			return true;
		}

		if ($this->result && $this->result->wasMarkedComplete()) {
			return true;
		}

		return $this->count !== Batch::UNKNOWN_COUNT && $this->processed >= $this->count;
	}
}