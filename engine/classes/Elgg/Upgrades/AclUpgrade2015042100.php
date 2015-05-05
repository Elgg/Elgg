<?php

namespace Elgg\Upgrades;

/**
 * This is an example of an upgrade that needs to be run in multiple batches.
 *
 * Currently just returns dummy data that can be used to test the UI.
 */
class AclUpgrade2015042100 implements BatchUpgrade {
	private $offset;

	private $errors = 0;

	public function getTitle() {
		return 'Comment ACL upgrade';
	}

	public function getDescription() {
		return 'The access control lists of some comments needs to be updated to match their container.';
	}

	public function isRequired() {
		return true;
	}

	/**
	 * Get total amount of items in need of upgrade
	 */
	public function getTotal() {
		return 9000;
	}

	public function setOffset($offset = null) {
		$this->offset = $offset;
	}

	public function run($offset = null) {
		$this->offset = $offset;

		// This simulates errors during the upgrade
		if (rand(1, 3) === 1) {
			register_error('There was an error ' . time());
			$this->errors++;
		}
	}

	public function getErrorCount() {
		return 10;
	}

	public function getSuccessCount() {
		return 2990;
	}

	public function getNextOffset() {
		return $this->offset + $this->getErrorCount();
	}

	public function getVersion() {
		return 2015042100;
	}

	public function getRelease() {
		return '2.0.0-dev';
	}
}
