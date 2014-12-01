<?php
namespace Elgg;
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
class Access {
	/**
	 * Bypass Elgg's access control if true.
	 * @var bool
	 */
	private $ignore_access;
	
	/** @var Elgg\Database\UsersTable */
	private $usersTable;
	
	/** @var \ElggStaticVariableCache */
	private $cache;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->cache = new \ElggStaticVariableCache('access');
		$this->usersTable = _elgg_services()->usersTable;
	}

	// @codingStandardsIgnoreStart
	/**
	 * Get current ignore access setting.
	 *
	 * @return bool
	 * @deprecated 1.8 Use \Elgg\Access::getIgnoreAccess()
	 */
	public function get_ignore_access() {
		elgg_deprecated_notice('\Elgg\Access::get_ignore_access() is deprecated by \Elgg\Access::getIgnoreAccess()', 1.8);
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
	 * @deprecated 1.8 Use \Elgg\Access:setIgnoreAccess()
	 */
	public function set_ignore_access($ignore = true) {
		elgg_deprecated_notice('\Elgg\Access::set_ignore_access() is deprecated by \Elgg\Access::setIgnoreAccess()', 1.8);
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
		$this->cache->clear();
		$prev = $this->ignore_access;
		$this->ignore_access = $ignore;

		return $prev;
	}
	
	/**
	 * Decides if the access system should be ignored for a user.
	 *
	 * Returns true (meaning ignore access) if either of these 2 conditions are true:
	 *   1) an admin user guid is passed to this function.
	 *   2) {@link elgg_get_ignore_access()} returns true.
	 *
	 * @see elgg_set_ignore_access()
	 *
	 * @param int $user_guid The user to check against.
	 *
	 * @return bool
	 * @since 1.7.0
	 * @todo should this be a private function?
	 */
	public function checkAccessOverrides($user_guid = 0) {
		if (!$user_guid || $user_guid <= 0) {
			$is_admin = false;
		} else {
			$is_admin = $this->usersTable->isAdmin($user_guid);
		}
	
		return ($is_admin || $this->getIgnoreAccess());
	}
}

