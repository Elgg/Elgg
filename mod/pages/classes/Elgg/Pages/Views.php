<?php

namespace Elgg\Pages;

/**
 * Event callbacks for views
 *
 * @since 4.0
 * @internal
 */
class Views {

	/**
	 * Prevent ACCESS_PUBLIC from ending up as a write access option
	 *
	 * @param \Elgg\Event $event 'view_vars', 'input/access'
	 *
	 * @return void|array
	 */
	public static function preventAccessPublic(\Elgg\Event $event) {
		$return = $event->getValue();
		if (elgg_extract('name', $return) !== 'write_access_id' || elgg_extract('purpose', $return) !== 'write') {
			return;
		}
		
		$value = (int) elgg_extract('value', $return);
		if ($value !== ACCESS_PUBLIC && $value !== ACCESS_DEFAULT) {
			return;
		}
		
		$default_access = elgg_get_default_access();
		
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
	 * @param \Elgg\Event $event 'access:collections:write', 'user'
	 *
	 * @return void|array
	 */
	public static function removeAccessPublic(\Elgg\Event $event) {
		
		$input_params = $event->getParam('input_params');
		$return_value = $event->getValue();
		
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
