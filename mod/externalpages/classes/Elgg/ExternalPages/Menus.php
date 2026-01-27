<?php

namespace Elgg\ExternalPages;

/**
 * Event callbacks for menus
 *
 * @since 7.0
 *
 * @internal
 */
class Menus {

	/**
	 * Adds menu items to the footer menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:[footer|walled_garden]'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$return = $event->getValue();
		$menu_name = elgg_extract('name', $event->getParams());
		
		$pages = \ElggExternalPage::getAllowedPageNames();
		foreach ($pages as $page) {
			$return[] = \ElggMenuItem::factory([
				'name' => $page,
				'text' => elgg_language_key_exists("external_pages:{$page}") ? elgg_echo("external_pages:{$page}") : $page,
				'href' => elgg_generate_url("view:object:external_page:{$page}"),
				'section' => $menu_name === 'footer' ? 'meta' : 'default',
			]);
		}

		return $return;
	}
}
