<?php

namespace Elgg\Upgrade;

class TestBatch implements \Elgg\Upgrade\Batch {

	const INCREMENT_OFFSET = true;
	const VERSION = 2016101900;

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
