<?php

namespace Elgg;

use ElggUpgrade;
use Elgg\Config;
use Elgg\Timer;
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
	 * @var $upgrade ElggUpgrade
	 */
	private $upgrade;

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

		// Custom limit can be defined in settings.php if necessary
		if (empty($this->config->get('batch_run_time_in_secs'))) {
			$this->config->set('batch_run_time_in_secs', 4);
		}
	}

	/**
	 * Set ElggUpgrade object
	 *
	 * @param ElggUpgrade $upgrade ElggEntity representing the upgrade
	 * @return void
	 */
	public function setUpgrade(ElggUpgrade $upgrade) {
		$this->upgrade = $upgrade;
	}

	/**
	 * Run single upgrade batch
	 *
	 * @return void
	 */
	public function run() {
		// Upgrade also disabled data, so the compatibility is
		// preserved in case the data ever gets enabled again
		global $ENTITY_SHOW_HIDDEN_OVERRIDE;
		$ENTITY_SHOW_HIDDEN_OVERRIDE = true;

		// Defined in Elgg\Application
		global $START_MICROTIME;

		$result = new Result;

		// Get the class taking care of the actual upgrading
		$upgrade = $this->upgrade->getUpgrade();

		do {
			$upgrade->run($result, $this->upgrade->offset);

			$failure_count = $result->getFailureCount();
			$success_count = $result->getSuccessCount();

			$total = $failure_count + $success_count;

			if ($upgrade::INCREMENT_OFFSET) {
				// Offset needs to incremented by the total amount of processed
				// items so the upgrade we won't get stuck upgrading the same
				// items over and over.
				$this->upgrade->offset += $total;
			} else {
				// Offset doesn't need to be incremented, so we mark only
				// the items that caused a failure.
				$this->upgrade->offset += $failure_count;
			}

			if ($failure_count > 0) {
				$this->upgrade->has_errors = true;
			}

			$this->upgrade->processed += $total;
		} while ((microtime(true) - $START_MICROTIME) < $this->config->get('batch_run_time_in_secs'));

		if ($this->upgrade->processed >= $this->upgrade->total) {
			// Upgrade is finished
			if ($this->upgrade->has_errors) {
				// The upgrade was finished with errors. Reset offset
				// and errors so the upgrade can start from a scratch
				// if attempted to run again.
				$this->upgrade->offset = 0;
				$this->upgrade->has_errors = false;

				// TODO Should $this->upgrade->count be updated again?
			} else {
				// Everything has been processed without errors
				// so the upgrade can be marked as completed.
				$this->upgrade->setCompleted();
			}
		}

		// Give feedback to the user interface about the current batch.
		return array(
			'errors' => $result->getErrors(),
			'numErrors' => $failure_count,
			'numSuccess' => $success_count,
		);
	}
}
