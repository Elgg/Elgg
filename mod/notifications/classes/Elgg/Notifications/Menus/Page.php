<?php

namespace Elgg\Notifications\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Page {

	/**
	 * Register menu items for the page menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		
		if (!elgg_in_context('settings') || !elgg_get_logged_in_user_guid()) {
			return;
		}
	
		$user = elgg_get_page_owner_entity();
		if (!$user) {
			$user = elgg_get_logged_in_user_entity();
		}
		
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => '2_a_user_notify',
			'text' => elgg_echo('notifications:subscriptions:changesettings'),
			'href' => elgg_generate_url('settings:notification:personal', [
				'username' => $user->username,
			]),
			'section' => 'notifications',
		]);
		
		if (elgg_is_active_plugin('groups')) {
			$return[] = \ElggMenuItem::factory([
				'name' => '2_group_notify',
				'text' => elgg_echo('notifications:subscriptions:changesettings:groups'),
				'href' => elgg_generate_url('settings:notification:groups', [
					'username' => $user->username,
				]),
				'section' => 'notifications',
			]);
		}
			
		return $return;
	}
}
