<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to the bulk actions for unvalidated users
 *
 * @since 4.0
 * @internal
 */
class UserUnvalidatedBulk {

	/**
	 * Add the bulk actions
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:user:unvalidated:bulk'
	 *
	 * @return void|MenuItems
	 */
	public static function registerActions(\Elgg\Hook $hook) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'select_all',
			'text' => elgg_view('input/checkbox', [
				'name' => 'select_all',
				'label' => elgg_echo('all'),
				'id' => 'admin-users-unvalidated-bulk-select',
			]),
			'href' => false,
			'priority' => 100,
			'deps' => 'admin/users/unvalidated',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'id' => 'admin-users-unvalidated-bulk-validate',
			'name' => 'bulk_validate',
			'text' => elgg_echo('validate'),
			'href' => elgg_generate_action_url('admin/user/bulk/validate'),
			'confirm' => true,
			'priority' => 400,
			'section' => 'right',
			'deps' => 'admin/users/unvalidated',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'id' => 'admin-users-unvalidated-bulk-delete',
			'name' => 'bulk_delete',
			'text' => elgg_echo('delete'),
			'href' => elgg_generate_action_url('admin/user/bulk/delete'),
			'confirm' => elgg_echo('deleteconfirm:plural'),
			'priority' => 500,
			'section' => 'right',
			'deps' => 'admin/users/unvalidated',
		]);
		
		return $return;
	}
}
