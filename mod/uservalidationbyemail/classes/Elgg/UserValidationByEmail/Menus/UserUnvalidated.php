<?php

namespace Elgg\UserValidationByEmail\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class UserUnvalidated {

	/**
	 * Add a menu item to an unvalidated user
	 *
	 * @param \Elgg\Hook $hook the plugin hook 'register' 'menu:user:unvalidated'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggUser) {
			return;
		}
		
		$validated = elgg_get_plugin_user_setting('email_validated', $entity->guid, 'uservalidationbyemail');
		if (!isset($validated) || (bool) $validated) {
			// email address already validated
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'uservalidationbyemail:resend',
			'text' => elgg_echo('uservalidationbyemail:admin:resend_validation'),
			'href' => elgg_generate_action_url('uservalidationbyemail/resend_validation', [
				'user_guids[]' => $entity->guid,
			]),
			'confirm' => elgg_echo('uservalidationbyemail:confirm_resend_validation', [$entity->getDisplayName()]),
			'priority' => 100,
		]);
		
		return $return;
	}
}
