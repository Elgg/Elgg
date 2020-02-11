<?php

namespace Elgg\Messages\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Page {

	/**
	 * Registers menu items
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		$user = elgg_get_logged_in_user_entity();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		if (!elgg_in_context('messages')) {
			return;
		}
		
		$result = $hook->getValue();
			
		$result['inbox'] = \ElggMenuItem::factory([
			'name' => 'messages:inbox',
			'text' => elgg_echo('messages:inbox'),
			'href' => elgg_generate_url('collection:object:messages:owner', [
				'username' => $user->username,
			]),
		]);
		
		$result['sent'] = \ElggMenuItem::factory([
			'name' => 'messages:sentmessages',
			'text' => elgg_echo('messages:sentmessages'),
			'href' => elgg_generate_url('collection:object:messages:sent', [
				'username' => $user->username,
			]),
		]);
				
		return $result;
	}
}
