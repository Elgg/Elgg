<?php

namespace Elgg\UserValidationByEmail;

use Elgg\Exceptions\Http\LoginException;

/**
 * Event callbacks for users
 *
 * @since 4.0
 * @internal
 */
class User {

	/**
	 * Disables a user upon registration
	 *
	 * @param \Elgg\Event $event 'register', 'user'
	 *
	 * @return void
	 */
	public static function disableUserOnRegistration(\Elgg\Event $event) {
		
		$user = $event->getUserParam();
		// no clue what's going on, so don't react.
		if (!$user instanceof \ElggUser) {
			return;
		}
	
		// another plugin is requesting that registration be terminated
		// no need for uservalidationbyemail
		if (!$event->getValue()) {
			return;
		}
	
		// has the user already been validated?
		if ($user->isValidated()) {
			return;
		}
	
		// disable user to prevent showing up on the site
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use ($user) {
			if ($user->isEnabled()) {
				// Don't do a recursive disable.  Any entities owned by the user at this point
				// are products of plugins that listen to the create user event and might need
				// access to the entities.
				// @todo That ^ sounds like a specific case...would be nice to track it down...
				$user->disable('uservalidationbyemail_new_user', false);
			}
		
			// set user as unvalidated
			$user->setValidationStatus(false);
			
			// set flag for tracking validation status
			$user->setPluginSetting('uservalidationbyemail', 'email_validated', false);
			
			// send out validation email
			uservalidationbyemail_request_validation($user->guid);
		});
	}
	
	/**
	 * Prevent a manual code login with elgg_login()
	 *
	 * @param \Elgg\Event $event 'login:before', 'user'
	 *
	 * @return void
	 *
	 * @throws LoginException
	 */
	public static function preventLogin(\Elgg\Event $event) {
		$user = $event->getObject();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($user) {
			if ($user->isEnabled() && $user->isValidated() !== false) {
				return;
			}
			
			if ((bool) elgg_get_plugin_user_setting('email_validated', $user->guid, 'uservalidationbyemail', true)) {
				// email address already validated, or account created before plugin was enabled
				return;
			}

			$exception = new LoginException();
			$exception->setRedirectUrl(elgg_http_get_signed_url(elgg_generate_url('account:validation:email:change', ['guid' => $user->guid]), '+5 minutes'));

			throw $exception;
		});
	}
}
