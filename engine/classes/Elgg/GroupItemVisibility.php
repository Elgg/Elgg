<?php

/**
 * Determines if otherwise visible items should be hidden from a user due to group
 * policy or visibility.
 *
 * @package    Elgg.Core
 * @subpackage Groups
 *
 * @access private
 */
class Elgg_GroupItemVisibility {

	const REASON_NON_MEMBER = 'non_member';
	const REASON_LOGGED_OUT = 'logged_out';
	const REASON_NO_ACCESS = 'no_access';

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
	 * @param int  $container_guid GUID of a container (may/may not be a group)
	 * @param bool $use_cache      Use the cached result of
	 *
	 * @return Elgg_GroupItemVisibility
	 *
	 * @todo Make this faster, considering it must run for every river item.
	 */
	static public function factory($container_guid, $use_cache = true) {
		// cache because this may be called repeatedly during river display, and
		// due to need to check group visibility, cache will be disabled for some
		// get_entity() calls
		static $cache = array();

		if (!$container_guid) {
			return new Elgg_GroupItemVisibility();
		}

		$user = elgg_get_logged_in_user_entity();
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

			$ret = new Elgg_GroupItemVisibility();

			if ($container && $container instanceof ElggGroup) {
				/* @var ElggGroup $container */

				if ($is_visible) {
					if ($container->getContentAccessMode() === ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
						if ($user) {
							if (!$container->isMember($user) && !$user->isAdmin()) {
								$ret->shouldHideItems = true;
								$ret->reasonHidden = self::REASON_NON_MEMBER;
							}
						} else {
							$ret->shouldHideItems = true;
							$ret->reasonHidden = self::REASON_LOGGED_OUT;
						}
					}
				} else {
					$ret->shouldHideItems = true;
					$ret->reasonHidden = self::REASON_NO_ACCESS;
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
