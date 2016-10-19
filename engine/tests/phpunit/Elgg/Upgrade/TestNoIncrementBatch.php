<?php

namespace Elgg\Upgrade;

class TestNoIncrementBatch implements \Elgg\Upgrade\Batch {
	
	const INCREMENT_OFFSET = false;
	const VERSION = 2016101901;

	private $count = 100;

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
