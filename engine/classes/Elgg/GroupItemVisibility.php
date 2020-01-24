<?php

namespace Elgg;

/**
 * Determines if otherwise visible items should be hidden from a user due to group
 * policy or visibility.
 *
 * @internal
 * @deprecated 3.0 Use ElggGroup::canAccessContent or Gatekeeper::assertAccessibleGroup
 */
class GroupItemVisibility {

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
	 * Added for deprecated notice
	 *
	 * @return void
	 */
	public function __construct() {
		elgg_deprecated_notice('The usage of \Elgg\GroupItemVisibility is deprecated, use ElggGroup::canAccessContent', '3.0', 2);
	}

	/**
	 * Determine visibility of items within a container for the current user
	 *
	 * @param int  $container_guid GUID of a container (may/may not be a group)
	 * @param bool $use_cache      Use the cached result of
	 *
	 * @return \Elgg\GroupItemVisibility
	 *
	 * @deprecated 3.0 Use ElggGroup::canAccessContent
	 */
	public static function factory($container_guid, $use_cache = true) {
		// cache because this may be called repeatedly during river display, and
		// due to need to check group visibility, cache will be disabled for some
		// get_entity() calls
		static $cache = [];

		if (!$container_guid) {
			return new \Elgg\GroupItemVisibility();
		}

		$user = _elgg_services()->session->getLoggedInUser();
		$user_guid = $user ? $user->guid : 0;

		$container_guid = (int) $container_guid;

		$cache_key = "$container_guid|$user_guid";
		if (empty($cache[$cache_key]) || !$use_cache) {
			// compute

			$container = get_entity($container_guid);
			$is_visible = (bool) $container;

			if (!$is_visible) {
				// see if it *really* exists...
				$prev_access = _elgg_services()->session->setIgnoreAccess();
				$container = get_entity($container_guid);
				_elgg_services()->session->setIgnoreAccess($prev_access);
			}

			$ret = new \Elgg\GroupItemVisibility();

			if ($container && $container instanceof \ElggGroup) {
				/* @var \ElggGroup $container */

				if ($is_visible) {
					if ($container->getContentAccessMode() === \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
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
