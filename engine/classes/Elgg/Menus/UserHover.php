<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to the user_hover menu
 *
 * @since 4.0
 * @internal
 */
class UserHover {
	
	/**
	 * Add a link to the avatar edit page
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:user_hover'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAvatarEdit(\Elgg\Hook $hook) {
		$user = $hook->getEntityParam();
		if (!$user instanceof \ElggUser || !$user->canEdit()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'avatar:edit',
			'icon' => 'image',
			'text' => elgg_echo('avatar:edit'),
			'href' => elgg_generate_entity_url($user, 'edit', 'avatar'),
			'section' => (elgg_get_logged_in_user_guid() == $user->guid) ? 'action' : 'admin',
		]);
		
		return $return;
	}
	
	/**
	 * Register admin action
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:user_hover'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminActions(\Elgg\Hook $hook) {
		$user = $hook->getEntityParam();
		if (!$user instanceof \ElggUser || !elgg_is_admin_logged_in()) {
			return;
		}
		
		if ($user->guid === elgg_get_logged_in_user_guid()) {
			// admins can't perform actions on themselfs
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		// (un)ban
		if (!$user->isBanned()) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'ban',
				'icon' => 'ban',
				'text' => elgg_echo('ban'),
				'href' => elgg_generate_action_url('admin/user/ban', [
					'guid' => $user->guid,
				]),
				'confirm' => true,
				'section' => 'admin',
			]);
		} else {
			$return[] = \ElggMenuItem::factory([
				'name' => 'unban',
				'icon' => 'ban',
				'text' => elgg_echo('unban'),
				'href' => elgg_generate_action_url('admin/user/unban', [
					'guid' => $user->guid,
				]),
				'confirm' => true,
				'section' => 'admin',
			]);
		}
		
		// delete
		$return[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'icon' => 'delete',
			'text' => elgg_echo('delete'),
			'href' => elgg_generate_action_url('admin/user/delete', [
				'guid' => $user->guid,
			]),
			'confirm' => true,
			'section' => 'admin',
		]);
		
		// reset password
		$return[] = \ElggMenuItem::factory([
			'name' => 'resetpassword',
			'icon' => 'refresh',
			'text' => elgg_echo('resetpassword'),
			'href' => elgg_generate_action_url('admin/user/resetpassword', [
				'guid' => $user->guid,
			]),
			'confirm' => true,
			'section' => 'admin',
		]);
		
		// Toggle admin role
		$is_admin = $user->isAdmin();
		$return[] = \ElggMenuItem::factory([
			'name' => 'makeadmin',
			'icon' => 'level-up-alt',
			'text' => elgg_echo('makeadmin'),
			'href' => elgg_generate_action_url('admin/user/makeadmin', [
				'guid' => $user->guid,
			]),
			'confirm' => true,
			'section' => 'admin',
			'item_class' => $is_admin ? 'hidden' : null,
			'data-toggle' => 'removeadmin',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'removeadmin',
			'icon' => 'level-down-alt',
			'text' => elgg_echo('removeadmin'),
			'href' => elgg_generate_action_url('admin/user/removeadmin', [
				'guid' => $user->guid,
			]),
			'confirm' => true,
			'section' => 'admin',
			'item_class' => $is_admin ? null : 'hidden',
			'data-toggle' => 'makeadmin',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'settings:edit',
			'icon' => 'cogs',
			'text' => elgg_echo('settings:edit'),
			'href' => elgg_generate_url('settings:account', [
				'username' => $user->username,
			]),
			'section' => 'admin',
		]);
		
		return $return;
	}
}
