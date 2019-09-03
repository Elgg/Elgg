<?php

namespace Elgg\WebServices;

use Elgg\Menu\MenuItems;

/**
 * Add menu items to page menu
 *
 * @since 3.2
 */
class AdminPageMenu {
	
	/**
	 * Add menu items to the admin page menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|MenuItems
	 */
	public function __invoke(\Elgg\Hook $hook) {
		
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'webservices',
			'text' => elgg_echo('admin:configure_utilities:webservices'),
			'href' => false,
			'section' => 'configure',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'ws_list',
			'text' => elgg_echo('admin:configure_utilities:ws_list'),
			'href' => '/admin/configure_utilities/ws_list',
			'parent_name' => 'webservices',
			'section' => 'configure',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'ws_tokens',
			'text' => elgg_echo('admin:configure_utilities:ws_tokens'),
			'href' => '/admin/configure_utilities/ws_tokens',
			'parent_name' => 'webservices',
			'section' => 'configure',
		]);
		
		return $return;
	}
}
