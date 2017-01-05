<?php

namespace Elgg\Friends\Collections;

use Elgg\Hook;
use ElggMenuItem;
use ElggUser;

class PageMenuHandler {

	/**
	 * Adds collection sidebar menu items
	 *
	 * @elgg_plugin_hook register menu:page
	 *
	 * @param Hook $hook Hook
	 * @return ElggMenuItem[]
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
			'href' => "collections/owner/$user->username",
			'contexts' => ['friends'],
		]);

		return $return;
	}
}
