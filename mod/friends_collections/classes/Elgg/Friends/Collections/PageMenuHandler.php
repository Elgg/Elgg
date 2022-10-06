<?php

namespace Elgg\Friends\Collections;

/**
 * Register page menu item
 */
class PageMenuHandler {

	/**
	 * Adds collection sidebar menu items
	 *
	 * @param \Elgg\Event $event 'register' 'menu:page'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public function __invoke(\Elgg\Event $event) {

		$user = elgg_get_page_owner_entity();
		if (!$user instanceof \ElggUser || !$user->canEdit()) {
			return;
		}

		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'friends:view:collections',
			'text' => elgg_echo('friends:collections'),
			'href' => elgg_generate_url('collection:access_collection:friends:owner', [
				'username' => $user->username,
			]),
			'contexts' => ['friends'],
		]);

		return $return;
	}
}
