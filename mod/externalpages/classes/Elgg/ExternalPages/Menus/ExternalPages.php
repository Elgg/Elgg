<?php

namespace Elgg\ExternalPages\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class ExternalPages {

	/**
	 * Adds menu items to the external_pages edit form
	 *
	 * @param \Elgg\Event $event 'register', 'menu:external_pages'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$selected_page = $event->getParam('page');
		$return = $event->getValue();
		
		$pages = \ElggExternalPage::getAllowedPageNames();
		foreach ($pages as $page) {
			$return[] = \ElggMenuItem::factory([
				'name' => $page,
				'text' => elgg_echo("external_pages:{$page}"),
				'href' => elgg_http_add_url_query_elements('admin/configure_utilities/external_pages', [
					'page' => $page,
				]),
				'selected' => $page === $selected_page,
			]);
		}
		
		return $return;
	}
}
