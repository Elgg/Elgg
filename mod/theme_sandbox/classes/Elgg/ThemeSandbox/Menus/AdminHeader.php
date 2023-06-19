<?php

namespace Elgg\ThemeSandbox\Menus;

/**
 * Event callbacks for menus
 *
 * @since 5.1
 */
class AdminHeader {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return null|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event): ?\Elgg\Menu\MenuItems {
		if (!elgg_is_admin_logged_in()) {
			return null;
		}
		
		$return = $event->getValue();
				
		$return[] = \ElggMenuItem::factory([
			'name' => 'information:theme_sandbox',
			'text' => elgg_echo('admin:information:theme_sandbox'),
			'href' => elgg_generate_url('default:theme_sandbox'),
			'parent_name' => 'information',
			'target' => '_blank',
		]);
				
		return $return;
	}
}
