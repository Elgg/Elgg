<?php

namespace Elgg\Helpers\Upgrade;

use Elgg\Upgrade\Batch;
use Elgg\Upgrade\Result;

class TestNoIncrementBatch implements Batch {

	/**
	 * @var int
	 */
	private $count = 100;
	
	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2016101901;
	}

	/**
	 * {@inheritDoc}
	 */
	public function needsIncrementOffset(): bool {
		return false;
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
		return $this->count;
	}

	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		$result->addError($offset);
		$result->addSuccesses(15);
		$this->count -= 15;

		$result->addFailures(10);

		return $result;
	}
}
