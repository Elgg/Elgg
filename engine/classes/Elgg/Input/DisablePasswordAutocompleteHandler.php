<?php

namespace Elgg\Input;

/**
 * Disables password autocomplete for input/password
 *
 * @since 4.0
 */
class DisablePasswordAutocompleteHandler {
	
	/**
	 * Disable the autocomplete feature on password fields
	 *
	 * @param \Elgg\Event $event 'view_vars', 'input/password'
	 *
	 * @return void|array
	 */
	public function __invoke(\Elgg\Event $event) {
		if (!_elgg_services()->config->security_disable_password_autocomplete) {
			return;
		}
		
		$return_value = $event->getValue();
		
		$return_value['autocomplete'] = 'off';
		
		return $return_value;
	}
}
