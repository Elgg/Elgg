<?php

namespace Elgg\Helpers\Upgrade;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * @see \Elgg\Upgrade\LocatorIntegrationTest
 */
class UpgradeLocatorTestBatch implements AsynchronousUpgrade {
	
	protected $_version;
	
	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		if (!isset($this->_version)) {
			$this->_version = date('Ymd') . rand(10, 99);
		}
		
		return $this->_version;
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
		return 100;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		$result->addSuccesses(10);
		
		return $result;
	}
}
