<?php

namespace Elgg\Helpers\Upgrade;

use Elgg\Upgrade\Result;

class NonRequiredTestBatch implements \Elgg\Upgrade\Batch {

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
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function countItems(): int {
		return Self::UNKNOWN_COUNT;
	}

	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		return $result;
	}
}
