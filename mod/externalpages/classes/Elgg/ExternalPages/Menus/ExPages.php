<?php

namespace Elgg\ExternalPages\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class ExPages {

	/**
	 * Adds menu items to the expages edit form
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:expages'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		$type = $hook->getParam('type');
		$return = $hook->getValue();
		
		$pages = ['about', 'terms', 'privacy'];
		foreach ($pages as $page) {
			$return[] = \ElggMenuItem::factory([
				'name' => $page,
				'text' => elgg_echo("expages:{$page}"),
				'href' => elgg_http_add_url_query_elements('admin/configure_utilities/expages', [
					'type' => $page,
				]),
				'selected' => $page === $type,
			]);
		}
		return $return;
	}
}
