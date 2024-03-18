<?php

namespace Elgg\Bin\Menus;

use Elgg\Menu\MenuItems;

/**
 * Event callbacks for menus
 *
 * @since 6.0
 * @internal
 */
class Topbar {
	
	/**
	 * Register menu items for the topbar menu
	 *
	 * @param \Elgg\Event $event 'register' 'menu:topbar'
	 *
	 * @return null|MenuItems
	 */
	public static function register(\Elgg\Event $event): ?MenuItems {
		$viewer = elgg_get_logged_in_user_entity();
		if (!$viewer instanceof \ElggUser) {
			 return null;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'bin',
			'icon' => 'trash',
			'text' => elgg_echo('default:bin'),
			'href' => elgg_generate_url('default:bin', [
				'username' => $viewer->username,
			]),
			'parent_name' => 'account',
			'section' => 'alt',
			'priority' => 800,
		]);
		
		return $return;
	}
}
