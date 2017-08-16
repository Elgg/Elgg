<?php

namespace Elgg\Upgrade;

class TestBatch implements Batch {

	public function getVersion() {
		return 2016101900;
	}

	public function needsIncrementOffset() {
		return true;
	}

	public function shouldBeSkipped() {
		return false;
	}

	public function countItems() {
		return 100;
	}

	public function run(Result $result, $offset) {
		$result->addError($offset);
		$result->addSuccesses(15);
		$result->addFailures(10);
		return $result;
	}

}
