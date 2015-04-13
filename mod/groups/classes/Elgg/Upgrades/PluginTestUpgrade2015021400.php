<?php

namespace Elgg\Upgrades;

/**
 * This is an example of an upgrade that plugins can provide for Elgg's
 * upgrading feature.
 */
class PluginTestUpgrade2015021400 implements BatchUpgrade {

	private $offset;

	public function getTitle() {
		return 'This upgrades stuff and things in groups';
	}

	public function getDescription() {
		return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam euismod auctor dolor, eget porta leo pretium in. Vivamus eget scelerisque urna. Sed pretium tellus quis risus tincidunt, id tincidunt elit ornare.';
	}

	public function isRequired() {
		// This should be an actual database query or some
		// check made against the dataroot
		return true;
	}

	public function getTotal() {
		return 80000;
	}

	public function setOffset($offset = null) {
		$this->offset = $offset;
	}

	public function run() {

	}

	public function getErrorCount() {
		return 0;
	}

	public function getSuccessCount() {
		return 5000;
	}

	public function getNextOffset() {
		return $this->offset + $this->getErrorCount() + $this->getSuccessCount();
	}

	public function getVersion() {
		return 2015021400;
	}

	public function getRelease() {
		return '2.0.0-dev';
	}
}
