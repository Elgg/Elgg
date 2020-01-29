<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items for the admin_footer menu
 *
 * @since 4.0
 * @internal
 */
class AdminFooter {

	/**
	 * Add links to Elgg help resources
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:admin_footer'
	 *
	 * @return void|MenuItems
	 */
	public static function registerHelpResources(\Elgg\Hook $hook) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'faq',
			'text' => elgg_echo('admin:footer:faq'),
			'href' => 'http://learn.elgg.org/en/stable/appendix/faqs.html',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'manual',
			'text' => elgg_echo('admin:footer:manual'),
			'href' => 'http://learn.elgg.org/en/stable/admin/index.html',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'community_forums',
			'text' => elgg_echo('admin:footer:community_forums'),
			'href' => 'https://elgg.org/groups/all/',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'blog',
			'text' => elgg_echo('admin:footer:blog'),
			'href' => 'https://elgg.org/blog/all',
		]);
		
		return $return;
	}
}
