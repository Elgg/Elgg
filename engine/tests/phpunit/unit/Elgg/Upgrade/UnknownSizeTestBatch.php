<?php

namespace Elgg\Upgrade;

class UnknownSizeTestBatch implements Batch {

	private $i = 0;

	public function getVersion() {
		return 2016101902;
	}

	public function needsIncrementOffset() {
		return true;
	}

	public function shouldBeSkipped() {
		return false;
	}

	public function countItems() {
		return Batch::UNKNOWN_COUNT;
	}

	public function run(Result $result, $offset) {
		$result->addSuccesses(10);
		$this->i++;
		if ($this->i === 2) {
			$result->markComplete();
		}

		return $result;
	}
}
