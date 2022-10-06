<?php

namespace Elgg\Members\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Members {

	/**
	 * Registers members filter menu items
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter:members'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'all',
			'text' => elgg_echo('all'),
			'href' => elgg_generate_url('collection:user:user'),
		]);
		$result[] = \ElggMenuItem::factory([
			'name' => 'popular',
			'text' => elgg_echo('sort:popular'),
			'href' => elgg_generate_url('collection:user:user:popular'),
		]);
		$result[] = \ElggMenuItem::factory([
			'name' => 'online',
			'text' => elgg_echo('members:label:online'),
			'href' => elgg_generate_url('collection:user:user:online'),
		]);
		
		$query = get_input('member_query');
		if (!empty($query)) {
			$result[] = \ElggMenuItem::factory([
				'name' => 'search',
				'text' => elgg_echo('members:label:search'),
				'href' => elgg_generate_url('search:user:user', [
					'member_query' => $query,
				]),
			]);
		}
		
		return $result;
	}
}
