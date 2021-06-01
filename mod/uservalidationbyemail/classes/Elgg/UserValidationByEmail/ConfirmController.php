<?php

namespace Elgg\UserValidationByEmail;

use Elgg\Exceptions\LoginException;
use Elgg\Http\ResponseBuilder;

/**
 * Controller to handle the 'account:validation:email:confirm' route
 *
 * @since 3.1
 */
class ConfirmController {

	/**
	 * Execute e-mail confirmation
	 *
	 * @param \Elgg\Request $request the Elgg request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(\Elgg\Request $request) {
		
		// new users are not enabled by default.
		return elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($request) {
			$user_guid = (int) $request->getParam('u', false);
			
			$user = get_user($user_guid);
			if (!$user) {
				return elgg_error_response(elgg_echo('email:confirm:fail'));
			}
			
			elgg_call(ELGG_IGNORE_ACCESS, function() use ($user) {
				$user->setPluginSetting('uservalidationbyemail', 'email_validated', true);
				
				if (!(bool) elgg_get_config('require_admin_validation')) {
					// user doesn't have to be validated by admin after this
					$user->setValidationStatus(true, 'email');
				}
			});
			
			try {
				login($user);
			} catch (LoginException $e) {
				return elgg_error_response($e->getMessage());
			}
			
			return elgg_ok_response('', elgg_echo('email:confirm:success'), elgg_get_site_url());
		});
	}
}
