<?php

namespace Elgg\Groups;

/**
 * Handle page owner related hooks
 *
 * @since 4.0
 * @internal
 */
class PageOwner {

	/**
	 * Helper handler to correctly resolve page owners on group routes
	 *
	 * @param \Elgg\Hook $hook "page_owner", "system"
	 *
	 * @return int|void
	 */
	public static function detectPageOwner(\Elgg\Hook $hook) {
	
		$return = $hook->getValue();
		if ($return) {
			return;
		}
	
		$segments = _elgg_services()->request->getUrlSegments();
		$identifier = array_shift($segments);
	
		if ($identifier !== 'groups') {
			return;
		}
	
		$page = array_shift($segments);
	
		switch ($page) {
			case 'add' :
				$guid = array_shift($segments);
				if (!$guid) {
					$guid = elgg_get_logged_in_user_guid();
				}
				return $guid;
	
			case 'edit':
			case 'profile' :
			case 'invite' :
			case 'requests' :
			case 'members' :
			case 'profile' :
				$guid = array_shift($segments);
				if (!$guid) {
					return;
				}
				return $guid;
	
			case 'member' :
			case 'owner' :
			case 'invitations':
				$username = array_shift($segments);
				if ($username) {
					$user = get_user_by_username($username);
				} else {
					$user = elgg_get_logged_in_user_entity();
				}
				if (!$user) {
					return;
				}
				return $user->guid;
		}
	}
}
