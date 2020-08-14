<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to the login menu
 *
 * @since 4.0
 * @internal
 */
class Login {
	
	/**
	 * Add the registration menu item
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:login'
	 *
	 * @return void|MenuItems
	 */
	public static function registerRegistration(\Elgg\Hook $hook) {
		
		if (!_elgg_services()->config->allow_registration || _elgg_services()->config->elgg_maintenance_mode) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'register',
			'text' => elgg_echo('register'),
			'href' => elgg_get_registration_url(),
			'link_class' => 'registration_link',
		]);
		
		return $return;
	}
	
	/**
	 * Add the forgotten password menu item
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:login'
	 *
	 * @return void|MenuItems
	 */
	public static function registerResetPassword(\Elgg\Hook $hook) {
		
		if (_elgg_services()->config->elgg_maintenance_mode) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'forgotpassword',
			'text' => elgg_echo('user:password:lost'),
			'href' => elgg_generate_url('account:password:reset'),
			'link_class' => 'forgot_link',
		]);
		
		return $return;
	}
}
