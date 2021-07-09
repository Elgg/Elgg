<?php

namespace Elgg\Activity\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Site {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:site'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'activity',
			'icon' => 'clock-regular',
			'text' => elgg_echo('activity'),
			'href' => elgg_generate_url('default:river'),
		]);
		
		return $return;
	}
}
