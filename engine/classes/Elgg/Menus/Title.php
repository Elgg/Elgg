<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to the title menu
 *
 * @since 4.0
 * @internal
 */
class Title {
	
	/**
	 * Add a link to the avatar edit page
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:title'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAvatarEdit(\Elgg\Hook $hook) {
		$user = $hook->getEntityParam();
		if (!$user instanceof \ElggUser || !$user->canEdit()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'avatar:edit',
			'icon' => 'image',
			'text' => elgg_echo('avatar:edit'),
			'href' => elgg_generate_entity_url($user, 'edit', 'avatar'),
			'link_class' => ['elgg-button', 'elgg-button-action'],
		]);
		
		return $return;
	}
}
