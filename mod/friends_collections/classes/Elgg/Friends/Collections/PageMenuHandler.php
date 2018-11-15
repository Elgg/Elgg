<?php

namespace Elgg\Friends\Collections;

use Elgg\Hook;
use ElggMenuItem;
use ElggUser;

/**
 * Register page menu item
 */
class PageMenuHandler {

	/**
	 * Adds collection sidebar menu items
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:page'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public function __invoke(Hook $hook) {

		$user = elgg_get_page_owner_entity();
		if (!$user instanceof ElggUser || !$user->canEdit()) {
			return;
		}

		$return = $hook->getValue();
		
		$return[] = ElggMenuItem::factory([
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
