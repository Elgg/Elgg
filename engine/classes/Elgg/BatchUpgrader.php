<?php

namespace Elgg;

use Elgg\Upgrade\Batch;
use ElggUpgrade;
use Elgg\Upgrade\Result;

/**
 * Runs long running upgrades and gives feedback to UI after each batch.
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @since 3.0.0
 */
class BatchUpgrader {

	/**
	 * @var $config Config
	 */
	private $config;

	/**
	 * Constructor
	 *
	 * @param Config $config Site configuration
	 */
	public function __construct(Config $config) {
		$this->config = $config;

		// Custom limit can be defined in elgg-config/settings.php if necessary
		if (empty($this->config->get('batch_run_time_in_secs'))) {
			$this->config->set('batch_run_time_in_secs', 4);
		}
	}

	/**
	 * Call the upgrade's run() for a short period of time, or until it completes
	 *
	 * @param ElggUpgrade $upgrade Upgrade to run
	 * @return array
	 * @throws \RuntimeException
	 */
	public function run(ElggUpgrade $upgrade) {
		// Upgrade also disabled data, so the compatibility is
		// preserved in case the data ever gets enabled again
		$ha = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		$started = microtime(true);

		// Get the class taking care of the actual upgrading
		$batch = $upgrade->getBatch();
			
		if (!$batch) {
			throw new \RuntimeException(elgg_echo('admin:upgrades:error:invalid_batch', [$upgrade->title, $upgrade->guid]));
		}

		$count = $batch->countItems();
		
		$batch_failure_count = 0;
		$batch_success_count = 0;
		$errors = [];

		$processed = (int) $upgrade->processed;
		$offset = (int) $upgrade->offset;
		$has_errors = (bool) $upgrade->has_errors;

		/** @var Result $result */
		$result = null;

		$condition = function () use (&$count, &$processed, &$result, $started) {
			if ((microtime(true) - $started) >= $this->config->get('batch_run_time_in_secs')) {
				return false;
			}
			if ($result && $result->wasMarkedComplete()) {
				return false;
			}

			return ($count === Batch::UNKNOWN_COUNT || ($count > $processed));
		};
		
		while ($condition()) {
			$result = $batch->run(new Result(), $offset);

			$failure_count = $result->getFailureCount();
			$success_count = $result->getSuccessCount();

			$batch_failure_count += $failure_count;
			$batch_success_count += $success_count;

			$total = $failure_count + $success_count;
			
			if ($batch->needsIncrementOffset()) {
				// Offset needs to incremented by the total amount of processed
				// items so the upgrade we won't get stuck upgrading the same
				// items over and over.
				$offset += $total;
			} else {
				// Offset doesn't need to be incremented, so we mark only
				// the items that caused a failure.
				$offset += $failure_count;
			}

			if ($failure_count > 0) {
				$has_errors = true;
			}

			$processed += $total;

			$errors = array_merge($errors, $result->getErrors());
		}

		access_show_hidden_entities($ha);

		$upgrade->processed = $processed;
		$upgrade->offset = $offset;
		$upgrade->has_errors = $has_errors;
		
		$completed = ($result && $result->wasMarkedComplete()) || ($processed >= $count);
		if ($completed) {
			// Upgrade is finished
			if ($has_errors) {
				// The upgrade was finished with errors. Reset offset
				// and errors so the upgrade can start from a scratch
				// if attempted to run again.
				$upgrade->processed = 0;
				$upgrade->offset = 0;
				$upgrade->has_errors = false;
			} else {
				// Everything has been processed without errors
				// so the upgrade can be marked as completed.
				$upgrade->setCompleted();
			}
		}

		// Give feedback to the user interface about the current batch.
		return [
			'errors' => $errors,
			'numErrors' => $batch_failure_count,
			'numSuccess' => $batch_success_count,
			'isComplete' => $result && $result->wasMarkedComplete(),
		];
	}

}
