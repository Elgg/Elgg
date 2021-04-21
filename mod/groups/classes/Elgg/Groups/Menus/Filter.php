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
	
	/**
	 * Setup filter tabs on notification settings page
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:filter:settings/notifications'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerNotificationSettings(\Elgg\Hook $hook) {
	
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggUser || !$page_owner->canEdit()) {
			return;
		}
		
		/* @var $return \Elgg\Menu\MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'groups',
			'text' => elgg_echo('collection:group:group'),
			'href' => elgg_generate_url('settings:notification:groups', [
				'username' => $page_owner->username,
			]),
			'priority' => 300,
		]);
		
		return $return;
	}
}
