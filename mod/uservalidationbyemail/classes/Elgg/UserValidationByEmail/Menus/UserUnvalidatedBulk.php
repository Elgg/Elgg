<?php

namespace Elgg\UserValidationByEmail\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class UserUnvalidatedBulk {

	/**
	 * Add a menu item to the buld actions for unvalidated users
	 *
	 * @param \Elgg\Hook $hook the plugin hook 'register' 'menu:user:unvalidated:bulk'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'id' => 'uservalidationbyemail-bulk-resend',
			'name' => 'uservalidationbyemail:resend:bulk',
			'text' => elgg_echo('uservalidationbyemail:admin:resend_validation'),
			'href' => elgg_generate_action_url('uservalidationbyemail/resend_validation'),
			'confirm' => elgg_echo('uservalidationbyemail:confirm_resend_validation_checked'),
			'priority' => 100,
			'section' => 'right',
			'deps' => 'elgg/uservalidationbyemail',
		]);
		
		return $return;
	}
}
