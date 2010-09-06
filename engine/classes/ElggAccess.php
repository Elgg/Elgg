<?php
/**
 * Temporary class used to determing if access is being ignored
 */
class ElggAccess {
	/**
	 * Bypass Elgg's access control if true.
	 * @var bool
	 */
	private $ignore_access;

	/**
	 * Get current ignore access setting.
	 * @return bool
	 */
	public function get_ignore_access() {
		return $this->ignore_access;
	}

	/**
	 * Set ignore access.
	 *
	 * @param $ignore bool true || false to ignore
	 * @return bool Previous setting
	 */
	public function set_ignore_access($ignore = true) {
		$prev = $this->ignore_access;
		$this->ignore_access = $ignore;

		return $prev;
	}
}