<?php

namespace Elgg\Helpers\Upgrade;

use Elgg\Upgrade\Batch;
use Elgg\Upgrade\Result;

class UnknownSizeTestBatch implements Batch {

	/**
	 * @var int
	 */
	private $i = 0;

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2016101902;
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
		return Batch::UNKNOWN_COUNT;
	}

	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		$result->addSuccesses(10);
		$this->i++;
		if ($this->i === 2) {
			$result->markComplete();
		}

		return $result;
	}
}
