<?php

namespace Elgg\Groups\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Filter {
	
	/**
	 * Setup filter tabs on /groups/all page
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:filter:groups/all'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function registerGroupsAll(\Elgg\Hook $hook) {
	
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'newest',
			'text' => elgg_echo('sort:newest'),
			'href' => elgg_generate_url('collection:group:group:all', [
				'filter' => 'newest',
			]),
			'priority' => 200,
		]);
	
		$return[] = \ElggMenuItem::factory([
			'name' => 'alpha',
			'text' => elgg_echo('sort:alpha'),
			'href' => elgg_generate_url('collection:group:group:all', [
				'filter' => 'alpha',
			]),
			'priority' => 250,
		]);
	
		$return[] = \ElggMenuItem::factory([
			'name' => 'popular',
			'text' => elgg_echo('sort:popular'),
			'href' => elgg_generate_url('collection:group:group:all', [
				'filter' => 'popular',
			]),
			'priority' => 300,
		]);
	
		$return[] = \ElggMenuItem::factory([
			'name' => 'featured',
			'text' => elgg_echo('groups:featured'),
			'href' => elgg_generate_url('collection:group:group:all', [
				'filter' => 'featured',
			]),
			'priority' => 400,
		]);
		
		return $return;
	}
}
