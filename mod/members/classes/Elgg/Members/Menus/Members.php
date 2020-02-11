<?php

namespace Elgg\Members\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Members {

	/**
	 * Registers members filter menu items
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:filter:members'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		$result = $hook->getValue();
		
		$result['newest'] = \ElggMenuItem::factory([
			'name' => 'newest',
			'text' => elgg_echo('sort:newest'),
			'href' => elgg_generate_url('collection:user:user:newest'),
		]);
		$result['alpha'] =\ElggMenuItem::factory([
			'name' => 'alpha',
			'text' => elgg_echo('sort:alpha'),
			'href' => elgg_generate_url('collection:user:user:alpha'),
		]);
		$result['popular'] = \ElggMenuItem::factory([
			'name' => 'popular',
			'text' => elgg_echo('sort:popular'),
			'href' => elgg_generate_url('collection:user:user:popular'),
		]);
		$result['online'] = \ElggMenuItem::factory([
			'name' => 'online',
			'text' => elgg_echo('members:label:online'),
			'href' => elgg_generate_url('collection:user:user:online'),
		]);
		
		return $result;
	}
}
