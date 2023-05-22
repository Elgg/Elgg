<?php

namespace Elgg\UserValidationByEmail\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class UserUnvalidated {

	/**
	 * Add a menu item to an unvalidated user
	 *
	 * @param \Elgg\Event $event 'register' 'menu:user:unvalidated'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggUser) {
			return;
		}
		
		$validated = elgg_get_plugin_user_setting('email_validated', $entity->guid, 'uservalidationbyemail');
		if (!isset($validated) || (bool) $validated) {
			// email address already validated
			return;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'uservalidationbyemail:resend',
			'icon' => 'envelope',
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
