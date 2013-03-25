<?php
/**
 * Class used to determine if access is being ignored.
 *
 * @package    Elgg.Core
 * @subpackage Access
 * @access     private
 * @see        elgg_get_ignore_access()
 *
 * @todo       I don't remember why this was required beyond scope concerns.
 */
class Elgg_Access {
	/**
	 * Bypass Elgg's access control if true.
	 * @var bool
	 */
	private $ignore_access;

	// @codingStandardsIgnoreStart
	/**
	 * Get current ignore access setting.
	 *
	 * @return bool
	 * @deprecated 1.8 Use Elgg_Access::getIgnoreAccess()
	 */
	public function get_ignore_access() {
		elgg_deprecated_notice('Elgg_Access::get_ignore_access() is deprecated by Elgg_Access::getIgnoreAccess()', 1.8);
		return $this->getIgnoreAccess();
	}
	// @codingStandardsIgnoreEnd

	/**
	 * Get current ignore access setting.
	 *
	 * @return bool
	 */
	public function getIgnoreAccess() {
		return $this->ignore_access;
	}

	// @codingStandardsIgnoreStart
	/**
	 * Set ignore access.
	 *
	 * @param bool $ignore Ignore access
	 *
	 * @return bool Previous setting
	 *
	 * @deprecated 1.8 Use Elgg_Access:setIgnoreAccess()
	 */
	public function set_ignore_access($ignore = true) {
		elgg_deprecated_notice('Elgg_Access::set_ignore_access() is deprecated by Elgg_Access::setIgnoreAccess()', 1.8);
		return $this->setIgnoreAccess($ignore);
	}
	// @codingStandardsIgnoreEnd

	/**
	 * Set ignore access.
	 *
	 * @param bool $ignore Ignore access
	 *
	 * @return bool Previous setting
	 */
	public function setIgnoreAccess($ignore = true) {
		$prev = $this->ignore_access;
		$this->ignore_access = $ignore;

		return $prev;
	}
}
