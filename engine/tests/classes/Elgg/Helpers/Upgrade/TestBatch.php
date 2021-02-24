<?php

namespace Elgg\Helpers\Upgrade;

use Elgg\Upgrade\Batch;
use Elgg\Upgrade\Result;

class TestBatch implements Batch {

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2016101900;
	}

	/**
	 * {@inheritDoc}
	 */
	public function needsIncrementOffset(): bool {
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function shouldBeSkipped(): bool {
		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function countItems(): int {
		return 100;
	}

	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		$result->addError($offset);
		$result->addSuccesses(15);
		$result->addFailures(10);
		
		return $result;
	}
}
