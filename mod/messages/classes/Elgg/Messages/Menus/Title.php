<?php

namespace Elgg\Messages\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Title {
	
	/**
	 * Add to the user hover menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:title'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		
		$user = $event->getEntityParam();
		if (!elgg_is_logged_in() || !$user instanceof \ElggUser) {
			return;
		}
		
		if (elgg_get_logged_in_user_guid() === $user->guid) {
			return;
		}
		
		if ((bool) elgg_get_plugin_setting('friends_only', 'messages') && !$user->isFriendOf(elgg_get_logged_in_user_guid())) {
			return;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'send',
			'text' => elgg_echo('messages:sendmessage'),
			'icon' => 'mail',
			'href' => elgg_generate_url('add:object:messages', [
				'send_to' => $user->guid,
			]),
			'class' => ['elgg-button', 'elgg-button-action'],
		]);
	
		return $return;
	}
}
