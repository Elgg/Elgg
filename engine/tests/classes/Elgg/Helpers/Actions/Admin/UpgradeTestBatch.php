<?php

namespace Elgg\Helpers\Actions\Admin;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * @see \Elgg\Actions\Admin\UpgradeTest
 */
class UpgradeTestBatch implements AsynchronousUpgrade {
	
	protected $_version;
	
	public function getVersion() {
		if (!isset($this->_version)) {
			$this->_version = date('Ymd') . rand(10, 99);
		}
		
		return $this->_version;
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
		$result->addSuccesses(10);
	}
}
