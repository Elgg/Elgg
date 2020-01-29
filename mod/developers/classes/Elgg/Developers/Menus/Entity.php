<?php

namespace Elgg\Developers\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Entity {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerEntityExplorer(\Elgg\Hook $hook) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'entity_explorer',
			'href' => elgg_http_add_url_query_elements('admin/develop_tools/entity_explorer', [
				'guid' => $hook->getEntityParam()->guid,
			]),
			'text' => elgg_echo('developers:entity_explorer:inspect_entity'),
			'icon' => 'search',
		]);
		
		return $return;
	}
}
