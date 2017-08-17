<?php

namespace Elgg\Upgrade;

class TestNoIncrementBatch implements Batch {

	public function getVersion() {
		return 2016101901;
	}

	public function needsIncrementOffset() {
		return false;
	}

	private $count = 100;

	public function shouldBeSkipped() {
		return false;
	}

	public function countItems() {
		return $this->count;
	}

	public function run(Result $result, $offset) {
		$result->addError($offset);
		$result->addSuccesses(15);
		$this->count -= 15;

		$result->addFailures(10);

		return $result;
	}
}
