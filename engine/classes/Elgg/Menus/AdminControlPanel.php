<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items for the admin_control_panel menu
 *
 * @since 4.1
 * @internal
 */
class AdminControlPanel {

	/**
	 * Add admin control panel actions
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_control_panel'
	 *
	 * @return void|MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'invalidate',
			'text' => elgg_echo('admin:cache:invalidate'),
			'icon' => 'sync-alt',
			'href' => elgg_generate_action_url('admin/site/cache/invalidate'),
			'link_class' => 'elgg-button elgg-button-action',
		]);

		if (!_elgg_services()->mutex->isLocked('upgrade')) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'upgrade',
				'text' => elgg_echo('upgrade'),
				'icon' => 'cogs',
				'href' => 'upgrade.php',
				'link_class' => 'elgg-button elgg-button-action',
				'confirm' => true,
			]);
		} else {
			$return[] = \ElggMenuItem::factory([
				'name' => 'unlock_upgrade',
				'text' => elgg_echo('upgrade:unlock'),
				'icon' => 'unlock',
				'href' => elgg_generate_action_url('admin/site/unlock_upgrade'),
				'link_class' => 'elgg-button elgg-button-action',
				'confirm' => elgg_echo('upgrade:unlock:confirm'),
			]);
		}
		
		return $return;
	}
}
