<?php

namespace Elgg\Upgrade;

use \Elgg\Upgrade\Result;

/**
 * Long running upgrades should implement this interface
 *
 * @since 3.0.0
 */
interface Batch {

	/**
	 * Runs upgrade on a single batch of items
	 *
	 * @param Result $result Object that holds results of the batch
	 * @param int    $offset Starting point of the batch
	 * @return Result Instance of \Elgg\Upgrade\Result
	 */
	public function run(Result $result, $offset);

	/**
	 * Gets the amount of items that need to be upgraded
	 *
	 * @return int
	 */
	public function countItems();
}
