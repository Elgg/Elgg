<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to the footer menu
 *
 * @since 4.0
 * @internal
 */
class Footer {

	/**
	 * Add the rss menu item
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:footer'
	 *
	 * @return void|MenuItems
	 */
	public static function registerRSS(\Elgg\Hook $hook) {
		
		if (!elgg_is_logged_in() || !_elgg_has_rss_link()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'rss',
			'icon' => 'rss-square',
			'text' => elgg_echo('feed:rss'),
			'title' => elgg_echo('feed:rss:title'),
			'href' => elgg_http_add_url_query_elements(current_page_url(), [
				'view' => 'rss',
			]),
		]);
		
		return $return;
	}
	
	/**
	 * Add Elgg branding
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:footer'
	 *
	 * @return void|MenuItems
	 */
	public static function registerElggBranding(\Elgg\Hook $hook) {
		if (_elgg_services()->config->remove_branding) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'powered',
			'text' => elgg_echo('elgg:powered'),
			'href' => 'https://elgg.org',
			'title' => 'Elgg ' . elgg_get_version(true),
			'section' => 'meta',
			'priority' => 600,
		]);
		
		return $return;
	}
}
