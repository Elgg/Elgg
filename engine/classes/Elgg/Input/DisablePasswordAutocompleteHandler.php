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
	 * @param \Elgg\Hook $hook 'view_vars', 'input/password'
	 *
	 * @return void|array
	 */
	public function __invoke(\Elgg\Hook $hook) {
		if (!_elgg_services()->config->security_disable_password_autocomplete) {
			return;
		}
		
		$return_value = $hook->getValue();
		
		$return_value['autocomplete'] = 'off';
		
		return $return_value;
	}
}
