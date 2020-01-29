<?php

namespace Elgg\Pages;

/**
 * Hook callbacks for views
 *
 * @since 4.0
 * @internal
 */
class Views {

	/**
	 * Called on view_vars, input/access hook
	 * Prevent ACCESS_PUBLIC from ending up as a write access option
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'input/access'
	 *
	 * @return void|array
	 */
	public static function preventAccessPublic(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		if (elgg_extract('name', $return) !== 'write_access_id' || elgg_extract('purpose', $return) !== 'write') {
			return;
		}
		
		$value = (int) elgg_extract('value', $return);
		if ($value !== ACCESS_PUBLIC && $value !== ACCESS_DEFAULT) {
			return;
		}
		
		$default_access = get_default_access();
		
		if ($value === ACCESS_PUBLIC || $default_access === ACCESS_PUBLIC) {
			// is the value public, or default which resolves to public?
			// if so we'll set it to logged in, the next most permissible write access level
			$return['value'] = ACCESS_LOGGED_IN;
		}
		
		return $return;
	}
	
	/**
	 * Return options for the write_access_id input
	 *
	 * @param \Elgg\Hook $hook 'access:collections:write', 'user'
	 *
	 * @return void|array
	 */
	public static function removeAccessPublic(\Elgg\Hook $hook) {
		
		$input_params = $hook->getParam('input_params');
		$return_value = $hook->getValue();
		
		if (empty($input_params) || !isset($return_value[ACCESS_PUBLIC])) {
			return;
		}
		
		if (elgg_extract('entity_subtype', $input_params) !== 'page') {
			return;
		}
	
		if (elgg_extract('purpose', $input_params) !== 'write') {
			return;
		}
		
		unset($return_value[ACCESS_PUBLIC]);
		
		return $return_value;
	}
}
