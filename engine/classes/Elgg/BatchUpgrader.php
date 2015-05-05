<?php

namespace Elgg;

use ElggUpgrade;

class BatchUpgrader {

	/**
	 * @var $upgrade \Elgg\BatchUpgrade
	 */
	private $upgrade;

	/**
	 *
	 */
	public function setUpgrade(ElggUpgrade $upgrade) {
		$this->upgrade = $upgrade;
	}

	public function run() {
		// Upgrade also disabled data, so the compatibility is
		// preserved in case the data ever gets enabled again
		global $ENTITY_SHOW_HIDDEN_OVERRIDE;
		$ENTITY_SHOW_HIDDEN_OVERRIDE = true;

		// from engine/start.php
		global $START_MICROTIME;

		do {
			$this->upgrade->getUpgrade()->run();

			// TODO(juho) Remove after debugging
			sleep(1);

		} while ((microtime(true) - $START_MICROTIME) < $this->config->batch_run_time_in_secs);

		if ($this->upgrade->getUpgrade()->getNumRemaining() === 0) {
			// Upgrade is finished
			if ($this->upgrade->has_errors) {
				// The upgrade was finished with errors. Reset offset
				// and errors so the upgrade can start from a scratch
				// if attempted to run again.
				$this->upgrade->offset = 0;
				$this->upgrade->has_errors = false;
			} else {
				// Everything has been processed witout errors
				// so the upgrade can be marked as completed.
				$this->upgrade->setCompleted();
			}
		} else {
			// TODO(juho) make this less silly
			$this->upgrade->offset = $this->upgrade->getUpgrade()->getNextOffset();
			$errors = $this->upgrade->getUpgrade()->getErrorMessages();

			if ($errors) {
				$this->upgrade->has_errors = true;
			}
		}
	}

	/**
	 *
	 */
	public function getResult() {
		return array(
			'errors' => $this->upgrade->getUpgrade()->getErrorMessages(),
			'numSuccess' => $this->upgrade->getUpgrade()->getSuccessCount(),
		);
	}
}
