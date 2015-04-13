<?php

namespace Elgg\Upgrades;

/**
 * Removes log of upgrades run before Elgg 2.0
 */
class DeleteOldUpgradeData2015010400 implements Upgrade {

	public function isRequired() {
		return true;
	}

	public function getTitle() {
		return 'Prepares Elgg to use the new upgrading system';
	}

	public function getDescription() {
		return 'Prepares Elgg to use the new upgrading system.';
	}

	public function run() {
		datalist_set('processed_upgrades', null);

		// TODO return result of datalist_set() after debugging
		return false;
	}

	public function getVersion() {
		return 2015010400;
	}

	public function getRelease() {
		return '2.0.0-dev';
	}
}
