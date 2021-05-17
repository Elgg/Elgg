<?php

namespace Elgg\Mocks\Database;

class Mutex extends \Elgg\Database\Mutex {

	protected $locks = [];

	/**
	 * {@inheritDoc}
	 */
	public function lock(string $namespace): bool {
		$this->locks[$namespace] = true;
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isLocked(string $namespace): bool {
		return isset($this->locks[$namespace]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function unlock(string $namespace): void {
		$this->locks[$namespace] = false;
	}
}
