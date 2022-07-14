<?php

namespace Elgg\WebServices\PAM\User;

/**
 * Check the user token for API user authentication
 * Used for the 'user' policy
 *
 * @internal
 * @since 4.3
 */
class AuthToken {
	
	/**
	 * This examines whether an authentication token is present and
	 * returns true if it is present and is valid.
	 *
	 * The user also gets logged in.
	 *
	 * @return bool
	 */
	public function __invoke(): bool {
		$token = get_input('auth_token');
		if (empty($token)) {
			return false;
		}
		
		$validated_userid = _elgg_services()->usersApiSessionsTable->validateToken($token);
		if (empty($validated_userid)) {
			return false;
		}
		
		$user = get_user($validated_userid);
		// Not an elgg user
		if (!$user instanceof \ElggUser) {
			return false;
		}
		
		// User is banned
		if ($user->isBanned()) {
			return false;
		}
		
		// elgg_login() throws on failure, which is handled by the Authentication service
		elgg_login($user);
		
		return true;
	}
}
