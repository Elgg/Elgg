<?php

namespace Elgg\Upgrades;

/**
 * Represents an upgrade that needs to be run in multiple batches
 *
 * This should be used for long running upgrades and migrations
 * that process multiple items.
 *
 * The implementation is responsible for returning the correct offset
 * for the next batch.
 *
 * @since 2.0.0
 */
interface BatchUpgrade extends Upgrade {
	/**
	 * Get total amount of items requiring an upgrade
	 *
	 * @return int
	 */
	public function getTotal();

	/**
	 * Get amount of items that failed to get upgraded during the current batch
	 *
	 * @return int
	 */
	public function getErrorCount();

	/**
	 * Get amount of items upgraded successfull during the current batch
	 *
	 * @return int
	 */
	public function getSuccessCount();

	/**
	 * Offset of the next batch
	 *
	 * If the upgrade doesn't delete the original items, the return value should
	 * be the total amount successfully processed items.
	 *
	 * If the upgrade does delete the succesfully processed items, the return
	 * value should be the total amount of errors that have happened during the
	 * upgrade. This allows those items to be skipped in the next batch.
	 *
	 * @return int
	 */
	public function getNextOffset();
}
