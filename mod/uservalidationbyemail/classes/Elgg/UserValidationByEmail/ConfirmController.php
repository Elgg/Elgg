<?php

namespace Elgg\UserValidationByEmail;

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
			
			$user->setValidationStatus(true, 'email');
			
			elgg_push_context('uservalidationbyemail_validate_user');
			$user->enable();
			elgg_pop_context();
			
			try {
				login($user);
			} catch (\LoginException $e) {
				return elgg_error_response($e->getMessage());
			}
			
			return elgg_ok_response('', elgg_echo('email:confirm:success'), elgg_get_site_url());
		});
	}
}
