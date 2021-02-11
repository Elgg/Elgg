<?php

namespace Elgg\SiteNotifications\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add filter menu tabs when needed
 *
 * @since 4.0
 * @internal
 */
class Filter {
	
	/**
	 * Add tabs to the site notifications pages
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:filter:site_notifications'
	 *
	 * @return void|MenuItems
	 */
	public static function registerTabs(\Elgg\Hook $hook) {
		
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggUser) {
			return;
		}
		
		/* @var $result MenuItems */
		$result = $hook->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'owner',
			'text' => elgg_echo('site_notifications:unread'),
			'href' => elgg_generate_url('collection:object:site_notification:owner', [
				'username' => $page_owner->username,
			]),
		]);
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'read',
			'text' => elgg_echo('site_notifications:read'),
			'href' => elgg_generate_url('collection:object:site_notification:read', [
				'username' => $page_owner->username,
			]),
		]);
		
		return $result;
	}
}
