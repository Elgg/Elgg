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
	 * @param \Elgg\Event $event 'register', 'menu:user_hover'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAvatarEdit(\Elgg\Event $event) {
		$user = $event->getEntityParam();
		if (!$user instanceof \ElggUser || !$user->isEnabled() || !$user->canEdit()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
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
	 * @param \Elgg\Event $event 'register', 'menu:user_hover'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminActions(\Elgg\Event $event) {
		$user = $event->getEntityParam();
		if (!$user instanceof \ElggUser || !elgg_is_admin_logged_in()) {
			return;
		}
		
		if ($user->guid === elgg_get_logged_in_user_guid()) {
			// admins can't perform actions on themselfs
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		// delete
		$return[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'icon' => 'delete',
			'text' => elgg_echo('delete'),
			'href' => elgg_generate_url('delete:user', [
				'guid' => $user->guid,
			]),
			'section' => 'admin',
			'priority' => 999,
		]);
		
		if ($user->isValidated() === false) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'change_email',
				'icon' => 'edit',
				'text' => elgg_echo('admin:users:unvalidated:change_email'),
				'href' => elgg_http_add_url_query_elements('ajax/form/admin/user/change_email', [
					'user_guid' => $user->guid,
				]),
				'link_class' => 'elgg-lightbox',
				'section' => 'admin',
			]);
			
			$return[] = \ElggMenuItem::factory([
				'name' => 'validate',
				'icon' => 'check',
				'text' => elgg_echo('validate'),
				'href' => elgg_generate_action_url('admin/user/validate', [
					'user_guid' => $user->guid,
				]),
				'confirm' => true,
				'section' => 'admin',
			]);
		}
		
		if (!$user->isEnabled()) {
			// in certain admin cases
			return $return;
		}
		
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
	
	/**
	 * Register admin action to login as another user
	 *
	 * @param \Elgg\Event $event 'register', 'menu:user_hover|menu:entity'
	 *
	 * @return void|MenuItems
	 */
	public static function registerLoginAs(\Elgg\Event $event) {
		$user = $event->getEntityParam();
		$logged_in_user = elgg_get_logged_in_user_entity();
		
		if (!$user instanceof \ElggUser || $user->isBanned() || !$user->isEnabled()) {
			// no user, banned user or disabled user (is certain admin cases) is unable to login
			return;
		}
		
		if (!$logged_in_user instanceof \ElggUser || !$logged_in_user->isAdmin()) {
			// no admin user logged in
			return;
		}
		
		if ($logged_in_user->guid === $user->guid) {
			// don't show menu on self
			return;
		}
		
		if (!empty(elgg_get_session()->get('login_as_original_user_guid'))) {
			// don't show menu if already logged in as someone else
			return;
		}
		
		$menu = $event->getValue();
		
		$menu[] = \ElggMenuItem::factory([
			'name' => 'login_as',
			'icon' => 'sign-in-alt',
			'text' => elgg_echo('action:user:login_as'),
			'href' => elgg_generate_action_url('admin/user/login_as', [
				'user_guid' => $user->guid,
			]),
			'section' => $event->getType() === 'menu:user_hover' ? 'admin' : 'default',
		]);
		
		return $menu;
	}
}
