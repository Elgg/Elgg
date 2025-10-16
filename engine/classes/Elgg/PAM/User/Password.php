<?php

namespace Elgg\PAM\User;

use Elgg\Exceptions\Http\LoginException;

/**
 * PAM handler to authenticate a user based on username/password
 * Used for the 'user' policy
 *
 * @since 4.3
 * @internal
 */
class Password {
	
	/**
	 * Authenticate a user
	 *
	 * @param array $credentials the user credentials
	 *
	 * @return bool
	 * @throws LoginException
	 */
	public function __invoke(array $credentials): bool {
		if (!isset($credentials['username']) || !isset($credentials['password'])) {
			return false;
		}
		
		return elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($credentials) {
			$user = elgg_get_user_by_username($credentials['username']);
			if (!$user) {
				throw new LoginException(_elgg_services()->translator->translate('LoginException:UsernameFailure'));
			}
			
			$password_svc = _elgg_services()->passwords;
			$password = (string) $credentials['password'];
			$hash = (string) $user->password_hash;
			
			if (elgg_is_authentication_failure_limit_reached($user)) {
				throw new LoginException(_elgg_services()->translator->translate('LoginException:AccountLocked'));
			}
			
			if (!$password_svc->verify($password, $hash)) {
				elgg_register_authentication_failure($user);
				
				throw new LoginException(_elgg_services()->translator->translate('LoginException:PasswordFailure'));
			}
			
			if ($password_svc->needsRehash($hash)) {
				$user->setPassword($password);
			}
			
			return true;
		});
	}
}
