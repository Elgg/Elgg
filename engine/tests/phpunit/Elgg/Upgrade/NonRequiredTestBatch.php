<?php

namespace Elgg\Upgrade;

class NonRequiredTestBatch implements \Elgg\Upgrade\Batch {

	public function getVersion() {
		return 2016101900;
	}

	public function needsIncrementOffset() {
		return true;
	}

	public function shouldBeSkipped() {
		return true;
	}

	public function countItems() {

	}

	public function run(Result $result, $offset) {

	}

}
