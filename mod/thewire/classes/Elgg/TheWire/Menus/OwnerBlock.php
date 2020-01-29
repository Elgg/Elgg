<?php

namespace Elgg\TheWire\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class OwnerBlock {

	/**
	 * Add a menu item to an ownerblock
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:owner_block'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		
		$user = $hook->getEntityParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
	
		$return = $hook->getValue();
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
