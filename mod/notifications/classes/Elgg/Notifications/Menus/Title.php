<?php

namespace Elgg\Notifications\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Title {

	/**
	 * Register menu items for the title menu on group profiles
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:title'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		if (!elgg_is_active_plugin('groups')) {
			return;
		}
	
		$user = elgg_get_logged_in_user_entity();
		if (!$user) {
			return;
		}
	
		$items = $hook->getValue();
		
		$group = $hook->getEntityParam();
		if (!$group instanceof \ElggGroup || !$group->isMember($user)) {
			return;
		}
		
		$subscribed = false;
		$methods = elgg_get_notification_methods();
		foreach ($methods as $method) {
			$subscribed = check_entity_relationship($user->guid, 'notify' . $method, $group->guid);
			if ($subscribed) {
				break;
			}
		}
			
		$items[] = \ElggMenuItem::factory([
			'name' => 'notifications',
			'parent_name' => 'group-dropdown',
			'text' => elgg_echo('notifications:subscriptions:changesettings:groups'),
			'href' => elgg_generate_url('settings:notification:groups', [
				'username' => $user->username,
			]),
			'badge' => $subscribed ? elgg_echo('on') : elgg_echo('off'),
			'icon' => $subscribed ? 'bell' : 'bell-slash',
		]);
		
		return $items;
	}
}
