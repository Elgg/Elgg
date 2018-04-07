<?php

namespace Elgg\Mocks\Database;

class Mutex extends \Elgg\Database\Mutex {

	protected $locks = [];

	public function lock($namespace) {
		$this->locks[$namespace] = true;
		return true;
	}

	public function isLocked($namespace) {
		return isset($this->locks[$namespace]);
	}

	public function unlock($namespace) {
		$this->locks[$namespace] = false;
		return true;
	}
}