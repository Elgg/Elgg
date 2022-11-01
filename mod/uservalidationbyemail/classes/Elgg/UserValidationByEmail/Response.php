<?php

namespace Elgg\UserValidationByEmail;

/**
 * Event callbacks for responses
 *
 * @since 4.0
 * @internal
 */
class Response {

	/**
	 * Override the URL to be forwarded after registration
	 *
	 * @param \Elgg\Event $event 'response', 'action:register'
	 *
	 * @return void|\Elgg\Http\ResponseBuilder
	 */
	public static function redirectToEmailSent(\Elgg\Event $event) {
		if (!elgg_get_session()->get('emailsent')) {
			return;
		}
		
		$value = $event->getValue();
		$value->setForwardURL(elgg_generate_url('account:validation:email:sent'));
		
		return $value;
	}
}
