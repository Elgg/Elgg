<?php

namespace Elgg\Messages\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Topbar {

	/**
	 * Add inbox link to topbar
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:topbar'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		if (!elgg_is_logged_in()) {
			return;
		}
	
		$user = elgg_get_logged_in_user_entity();
	
		$text = elgg_echo('messages');
		$title = $text;
	
		$num_messages = (int) messages_count_unread();
		if ($num_messages) {
			$title .= " (" . elgg_echo("messages:unreadcount", [$num_messages]) . ")";
		}
	
		$items = $hook->getValue();
		$items[] = \ElggMenuItem::factory([
			'name' => 'messages',
			'href' => elgg_generate_url('collection:object:messages:owner', [
				'username' => $user->username,
			]),
			'text' => $text,
			'priority' => 600,
			'title' => $title,
			'icon' => 'mail',
			'badge' => $num_messages ?: null,
		]);
	
		return $items;
	}
}
