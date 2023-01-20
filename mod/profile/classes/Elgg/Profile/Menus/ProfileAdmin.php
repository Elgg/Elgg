<?php

namespace Elgg\Profile\Menus;

use Elgg\Menu\MenuItems;

/**
 * Event callbacks for menus
 *
 * @since 5.0
 */
class ProfileAdmin {
	
	/**
	 * Add menu items from the user_hover menu to the profile_admin menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:profile_admin'
	 *
	 * @return MenuItems|null
	 */
	public static function registerUserHover(\Elgg\Event $event): ?MenuItems {
		if (!elgg_is_admin_logged_in()) {
			return null;
		}
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggUser || $entity->guid === elgg_get_logged_in_user_guid()) {
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		// get the admin section of the user_hover menu
		$user_hover = elgg()->menus->getUnpreparedMenu('user_hover', [
			'entity' => $entity,
			'username' => $entity->username,
		]);
		
		$add_admin_toggle = false;
		/* @var $menu_item \ElggMenuItem */
		foreach ($user_hover->getItems() as $menu_item) {
			if ($menu_item->getSection() !== 'admin') {
				continue;
			}
			
			$add_admin_toggle = true;
			
			$menu_item->setSection('default');
			$menu_item->setParentName('admin_toggle');
			
			$result[] = $menu_item;
		}
		
		if ($add_admin_toggle) {
			$result[] = \ElggMenuItem::factory([
				'name' => 'admin_toggle',
				'text' => elgg_echo('admin:options'),
				'href' => false,
				'child_menu' => [
					'display' => 'toggle',
				],
			]);
		}
		
		return $result;
	}
}
