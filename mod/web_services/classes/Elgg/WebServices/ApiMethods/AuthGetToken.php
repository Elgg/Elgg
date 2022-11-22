<?php

namespace Elgg\WebServices\ApiMethods;

use Elgg\Exceptions\AuthenticationException;
use Elgg\Exceptions\SecurityException;

/**
 * Api handler for the auth.gettoken call
 *
 * This API call lets a user log in, returning an authentication token which can be used
 * to authenticate a user for a period of time.
 * It is passed in future calls as the parameter 'auth_token'.
 *
 * @since 4.0
 * @internal
 */
class AuthGetToken {
	
	/**
	 * Execute the api method
	 *
	 * @param string $username username
	 * @param string $password password
	 *
	 * @return string
	 * @throws SecurityException
	 */
	public function __invoke(string $username, string $password) {
		// also check if username is an email address
		$user = elgg_get_user_by_username($username, true);
		if ($user instanceof \ElggUser) {
			// could be fetched based on email address
			$username = $user->username;
		}
		
		try {
			// validate username and password
			$authenticated = elgg_pam_authenticate('user', [
				'username' => $username,
				'password' => $password,
			]);
			if ($authenticated === true && $user instanceof \ElggUser) {
				if ($user->isBanned()) {
					throw new SecurityException(elgg_echo('SecurityException:BannedUser'));
				}
				
				$token = _elgg_services()->usersApiSessionsTable->createToken($user->guid);
				if ($token !== false) {
					return $token;
				}
			}
		} catch (AuthenticationException $e) {
			// authentication failed
		}
		
		throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
	}
}
