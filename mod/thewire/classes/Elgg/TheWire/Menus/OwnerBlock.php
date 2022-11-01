<?php

namespace Elgg\TheWire\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class OwnerBlock {

	/**
	 * Add a menu item to an ownerblock
	 *
	 * @param \Elgg\Event $event 'register', 'menu:owner_block'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		
		$user = $event->getEntityParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
	
		$return = $event->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'thewire',
			'text' => elgg_echo('item:object:thewire'),
			'href' => elgg_generate_url('collection:object:thewire:owner', [
				'username' => $user->username,
			]),
		]);
		
		return $return;
	}
}
