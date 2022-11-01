<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;
use Elgg\Menu\PreparedMenu;
use Elgg\Menu\MenuSection;

/**
 * Register menu items to the bulk actions for users
 *
 * @since 4.2
 * @internal
 */
class AdminUsersBulk {

	/**
	 * Add the bulk actions
	 *
	 * @param \Elgg\Event $event 'register' 'menu:admin:users:bulk'
	 *
	 * @return void|MenuItems
	 */
	public static function registerActions(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		if ((bool) $event->getParam('show_ban', true)) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'ban',
				'icon' => 'ban',
				'text' => elgg_echo('ban'),
				'href' => elgg_generate_action_url('admin/user/bulk/ban', [], false),
				'priority' => 100,
			]);
		}
		
		if ((bool) $event->getParam('show_unban', true)) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'unban',
				'icon' => 'ban',
				'text' => elgg_echo('unban'),
				'href' => elgg_generate_action_url('admin/user/bulk/unban', [], false),
				'priority' => 200,
			]);
		}
		
		if ((bool) $event->getParam('show_validate', false)) {
			$return[] = \ElggMenuItem::factory([
				'icon' => 'check',
				'name' => 'validate',
				'text' => elgg_echo('validate'),
				'href' => elgg_generate_action_url('admin/user/bulk/validate'),
				'priority' => 400,
			]);
		}
		
		if ((bool) $event->getParam('show_delete', true)) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'delete',
				'icon' => 'delete',
				'text' => elgg_echo('delete'),
				'href' => elgg_generate_action_url('admin/user/bulk/delete', [], false),
				'confirm' => elgg_echo('deleteconfirm:plural'),
				'priority' => 900,
				'link_class' => ['elgg-button-delete'],
			]);
		}
		
		return $return;
	}
	
	/**
	 * Disable all items which have a href
	 *
	 * @param \Elgg\Event $event 'prepare', 'menu:admin:users:bulk'
	 *
	 * @return PreparedMenu|null
	 */
	public static function disableItems(\Elgg\Event $event): ?PreparedMenu {
		$menu = $event->getValue();
		if (!$menu instanceof PreparedMenu) {
			return null;
		}
		
		$disable = function(\ElggMenuItem $menu_item) use (&$disable) {
			if (!empty($menu_item->getHref())) {
				$menu_item->disabled = true;
			}
			
			foreach ($menu_item->getChildren() as $child) {
				$disable($child);
			}
		};
		
		/* @var $section MenuSection */
		foreach ($menu as $section) {
			/* @var $menu_item \ElggMenuItem */
			foreach ($section as $menu_item) {
				$disable($menu_item);
			}
		}
		
		return $menu;
	}
}
