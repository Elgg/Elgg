<?php

namespace Elgg\UserValidationByEmail;

/**
 * Hook callbacks for responses
 *
 * @since 4.0
 * @internal
 */
class Response {

	/**
	 * Override the URL to be forwarded after registration
	 *
	 * @param \Elgg\Hook $hook 'response', 'action:register'
	 *
	 * @return void|\Elgg\Http\ResponseBuilder
	 */
	public static function redirectToEmailSent(\Elgg\Hook $hook) {
		if (!elgg_get_session()->get('emailsent')) {
			return;
		}
		
		$value = $hook->getValue();
		$value->setForwardURL(elgg_generate_url('account:validation:email:sent'));
		
		return $value;
	}
}
