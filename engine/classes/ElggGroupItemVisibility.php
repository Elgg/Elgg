<?php

/**
 * Determines if otherwise visible items should be hidden from a user due to group
 * policy or visibility.
 *
 * @class      ElggGroupItemVisibility
 * @package    Elgg.Core
 * @subpackage Groups
 *
 * @access private
 */
class ElggGroupItemVisibility {

	const REASON_MEMBERSHIP = 'membershiprequired';
	const REASON_LOGGEDOUT = 'loggedinrequired';
	const REASON_NOACCESS = 'noaccess';

	/**
	 * @var bool
	 */
	public $shouldHideItems = false;

	/**
	 * @var string
	 */
	public $reasonHidden = '';

	/**
	 * Determine visibility of items within a container for the current user
	 *
	 * @param int $container_guid GUID of a container (may/may not be a group)
	 * @param Elgg_UserStatus $user_status
	 * @param bool $use_cache use the cached result of
	 *
	 * @return ElggGroupItemVisibility
	 *
	 * @todo Make this faster, considering it must run for every river item.
	 */
	static public function factory($container_guid, Elgg_UserStatus $user_status = null, $use_cache = true) {
		// cache because this may be called repeatedly during river display, and
		// due to need to check group visibility, cache will be disabled for some
		// get_entity() calls
		static $cache = array();

		$ret = new ElggGroupItemVisibility();

		if (!$container_guid) {
			return $ret;
		}

		if (!$user_status) {
			$user_status = Elgg_UserStatus::fromSession();
		}
		$user = $user_status->getUser();
		$user_guid = $user ? $user->guid : 0;

		$container_guid = (int) $container_guid;

		$cache_key = "$container_guid|$user_guid";
		if (empty($cache[$cache_key]) || !$use_cache) {
			// compute

			$container = get_entity($container_guid);
			$is_visible = (bool) $container;

			if (!$is_visible) {
				// see if it *really* exists...
				$prev_access = elgg_set_ignore_access();
				$container = get_entity($container_guid);
				elgg_set_ignore_access($prev_access);
			}

			if ($container && $container instanceof ElggGroup) {
				/* @var ElggGroup $container */

				if ($is_visible) {
					if ($container->getGatekeeperMode() === ElggGroup::GATEKEEPER_MODE_MEMBERSONLY) {
						if ($user) {
							if (!$container->isMember($user) && !$user->isAdmin()) {
								$ret->shouldHideItems = true;
								$ret->reasonHidden = self::REASON_MEMBERSHIP;
							}
						} else {
							$ret->shouldHideItems = true;
							$ret->reasonHidden = self::REASON_LOGGEDOUT;
						}
					}
				} else {
					$ret->shouldHideItems = true;
					$ret->reasonHidden = self::REASON_NOACCESS;
				}
			}
			$cache[$cache_key] = $ret;
		}

		$return = $cache[$cache_key];

		// don't exhaust memory in extreme uses
		if (count($cache) > 500) {
			reset($cache);
			unset($cache[key($cache)]);
		}

		return $return;
	}
}
