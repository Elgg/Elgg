<?php

namespace Elgg\Upgrade;

class NonRequiredTestBatch implements \Elgg\Upgrade\Batch {

	const INCREMENT_OFFSET = true;
	const VERSION = 2016101900;

	public function isRequired() {
		return false;
	}

	public function countItems() {

	}

	public function run(Result $result, $offset) {

	}

}
